<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

use \Symfony\Component\Lock\Factory;

// no direct access
defined('_JEXEC') or die('Restricted access');

class STMController extends JControllerLegacy
{
    private $fileHelper;
    private $projectHelper;
    private $scHelper;
    private $exportHelper;
    private $locker;

    /** @var ContentModelArticle $articleModel */
    private $articleModel;
    /** @var STMModelProfile $profileModel */
    private $profileModel;
    /** @var STMModelProjects $projectsModel */
    private $projectsModel;
    /** @var STMModelProject $projectModel */
    private $projectModel;
    private $logger;

    private $errorMessages = [];
    private $successMessages = [];

    /**
     * STMController constructor.
     *
     * @param array $config
     *
     * @since 1.0.0
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_content/models', 'ContentModel');
        JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/models', 'STMModel');

        $this->fileHelper = FileHelper::getInstance();
        $this->projectHelper = ProjectHelper::getInstance();
        $this->scHelper = SCHelper::getInstance();
        $this->exportHelper = ExportTranslationHelper::getInstance();
        $this->locker = new Factory(new PdoStore());

        $this->articleModel = parent::getModel('Article', 'ContentModel', array('ignore_request' => true));
        $this->profileModel = parent::getModel('Profile', 'STMModel', array('ignore_request' => true));
        $this->projectsModel = parent::getModel('Projects', 'STMModel', array('ignore_request' => true));
        $this->projectModel = parent::getModel('Project', 'STMModel', array('ignore_request' => true));
        $this->logger = new LoggerHelper();
    }

    /**
     * Sending projects in status 'waiting' to Smartcat
     *
     * @since 1.0.0
     */
    private function sendProjects()
    {
        $fieldNames = ['title', 'articletext'];
        $documents = [];
        $projectNames = [];

        $projects = $this->projectsModel->getByStatus(STMModelProject::STATUS_WAITING);

        if (empty($projects)) {
            $this->logger->event('send', 'No projects to send');
            $this->successMessages[] = 'No projects to send';
            return;
        }

        foreach ($projects as $index => $project) {
            $item = $this->articleModel->getItem($project->entity_id);
            $fields = [];

            foreach ($fieldNames as $name) {
                $fields[$name] = $item->$name;
            }

            $fileName = "{$item->title}_{$project->target_lang}.html";
            $file = $this->fileHelper->createFile($fields);

            if ($file) {
                $documents[$project->id] =
                    $this->projectHelper->createDocumentFromFile($file, $fileName, $project->id, $project->target_lang);
                $projectNames[] = $item->title;
            }
        }

        $profile = $this->profileModel->getItem($project->profile_id);
        $projectNames = array_unique($projectNames);
        $projectName = implode(', ', $projectNames);

        if (empty($documents)) {
            $this->logger->error('Can\'t send documents', 'No documents to send');
            $this->errorMessages[] = 'No documents to send';
            return;
        }

        try {
            if (empty($profile->project_guid)) {
                $scProject = $this->projectHelper->createProject($profile, $projectName);
                $profile->project_guid = $scProject->getId();
            }

            $scDocuments = $this->projectHelper->sendDocuments(array_values($documents), $profile->project_guid);
        } catch (\Exception $e) {
            $this->exceptionCatcher($e);
            return;
        }

        foreach ($scDocuments as $scDocument) {
            $project = $this->projectModel->getItem($scDocument->getExternalId());

            $data = [
                'id' => $project->id,
                'entity_id' => $project->entity_id,
                'profile_id' => $profile->id,
                'status' => $scDocument->getStatus(),
                'document_id' => $scDocument->getId(),
                'task_id' => null,
                'target_lang' => $scDocument->getTargetLanguage(),
            ];

            $this->projectModel->save($data);
        }

        $this->logger->event('check', sprintf("Sended %d projects", count($scDocuments)));
        $this->successMessages[] = sprintf("Sended %d projects", count($scDocuments));
    }

    /**
     * Checking projects statuses
     *
     * @since 1.0.0
     */
    private function checkProjects()
    {
        $updatedProjects = [];

        $projects = $this->projectsModel->getByStatus([
            STMModelProject::STATUS_CREATED,
            STMModelProject::STATUS_IN_PROGRESS
        ]);

        if (empty($projects)) {
            $this->logger->event('check', 'No projects to update');
            $this->successMessages[] = 'No projects to update';
            return;
        }

        foreach ($projects as $project) {
            try {
                $scDocument = $this->scHelper->getDocumentManager()->documentGet(["documentId" => $project->document_id]);
            } catch (\Exception $e) {
                $this->exceptionCatcher($e, $project);
                continue;
            }

            if ($scDocument->getStatus() != $project->status) {
                $data = [
                    'id' => $project->id,
                    'entity_id' => $project->entity_id,
                    'profile_id' => $project->profile_id,
                    'status' => $scDocument->getStatus(),
                    'document_id' => $project->document_id,
                    'task_id' => null,
                    'target_lang' => $project->target_lang,
                ];

                $this->projectModel->save($data);
                $updatedProjects[] = $project;
            }
        }

        $this->logger->event('check', sprintf("Updated %d projects", count($updatedProjects)));
        $this->successMessages[] = sprintf("Updated %d projects", count($updatedProjects));
    }

    /**
     * Requesting export documents if they are completed
     *
     * @since 1.0.0
     */
    private function requestProjects()
    {
        $requestedProjects = [];

        $projects = $this->projectsModel->getByStatus(STMModelProject::STATUS_COMPLETED);

        if (empty($projects)) {
            $this->logger->event('request', 'No projects to request');
            $this->successMessages[] = 'No projects to request';
            return;
        }

        foreach ($projects as $project) {
            try {
                $exportId = $this->exportHelper->requestExport($project->document_id);
            } catch (\Exception $e) {
                $this->exceptionCatcher($e, $project);
                continue;
            }

            $data = [
                'id' => $project->id,
                'entity_id' => $project->entity_id,
                'profile_id' => $project->profile_id,
                'status' => STMModelProject::STATUS_ON_EXPORT,
                'document_id' => $project->document_id,
                'task_id' => $exportId,
                'target_lang' => $project->target_lang,
            ];

            $this->projectModel->save($data);
            $requestedProjects[] = $project;
        }

        $this->logger->event('request', sprintf("Requested %d projects", count($requestedProjects)));
        $this->successMessages[] = sprintf("Requested %d projects", count($requestedProjects));
    }

    /**
     * Receive translated documents from Smartcat
     *
     * @since 1.0.0
     */
    private function receiveProjects()
    {
        $documentsDownloadError = [];
        $documentsDownloadSuccess = [];

        $projects = $this->projectsModel->getByStatus(STMModelProject::STATUS_ON_EXPORT);

        if (empty($projects)) {
            $this->logger->event('recieve', 'No projects to receive');
            $this->successMessages[] = 'No projects to receive';
            return;
        }

        foreach ($projects as $project) {
            try {
                $fields = $this->exportHelper->downloadDocs($project->task_id);
            } catch (\Exception $e) {
                if (!$this->exceptionCatcher($e, $project, true)) {
                    $documentsDownloadError[] = $project->document_id;
                }

                continue;
            }

            $article = JTable::getInstance('Content', 'JTable');

            if (!empty($fields)) {
                $article->load($project->entity_id);
                $article->language = LanguageDictionary::codeConvertToJoomla($project->target_lang);

                foreach ($fields as $field => $value) {
                    if ($field === 'title') {
                        $article->alias = $article->alias . '-' . $article->language;
                    }

                    if ($field === 'articletext') {
                        $field = 'introtext';
                    }

                    $article->$field = $value;
                }

                if ($article->check()) {
                    $article->id = null;

                    if ($article->store(true)) {
                        $documentsDownloadSuccess[] = $project->document_id;
                    } else {
                        $documentsDownloadError[] = $project->document_id;
                        $this->errorMessages[] = $article->getError();
                    }
                } else {
                    $documentsDownloadError[] = $project->document_id;
                    $this->errorMessages[] = $article->getError();
                }
            }
        }

        if (count($documentsDownloadError) > 0) {
            $result = $this->projectsModel->bulkUpdate(
                ['status' => STMModelProject::STATUS_FAILED],
                ['document_id' => $documentsDownloadError]
            );
            if (!$result) {
                $this->logger->error('Can\'t set status failed to failed documents', json_encode($documentsDownloadError));
                $this->errorMessages[] = 'Can\'t set status failed to failed documents: ' . json_encode($documentsDownloadError);
            }
        }

        if (count($documentsDownloadSuccess) > 0) {
            $result = $this->projectsModel->bulkUpdate(
                ['status' => STMModelProject::STATUS_DOWNLOADED],
                ['document_id' => $documentsDownloadSuccess]
            );
            if (!$result) {
                $this->logger->error('Can\'t set status downloaded to success documents', json_encode($documentsDownloadSuccess));
                $this->errorMessages[] = 'Can\'t set status downloaded to success documents: ' . json_encode($documentsDownloadSuccess);
            }
        }

        $this->logger->event('recieve', sprintf("Received %d projects", count($documentsDownloadSuccess)));
        $this->successMessages[] = sprintf("Received %d projects", count($documentsDownloadSuccess));
    }

    /**
     * Public controller for all cron tasks with JSON response
     *
     * @since 1.0.0
     */
    public function cron()
    {
        return $this->getResponse(function () {
            $this->logger->event('cron', 'Cron started');
            $this->checkProjects();
            $this->sendProjects();
            $this->requestProjects();
            $this->receiveProjects();
            $this->logger->event('cron', 'Cron ended');
        });
    }

    /**
     * Public controller for checkProjects with JSON response
     *
     * @since 1.0.0
     */
    public function check()
    {
        return $this->getResponse(function () {
            $this->logger->event('check', 'Check started');
            $this->checkProjects();
            $this->logger->event('check', 'Check started');
        });
    }

    /**
     * Public controller for sendProjects with JSON response
     *
     * @since 1.0.0
     */
    public function send()
    {
        return $this->getResponse(function () {
            $this->logger->event('send', 'Send started');
            $this->sendProjects();
            $this->logger->event('send', 'Send started');
        });
    }

    /**
     * Public controller for requestProjects with JSON response
     *
     * @since 1.0.0
     */
    public function request()
    {
        return $this->getResponse(function () {
            $this->logger->event('request', 'Request started');
            $this->requestProjects();
            $this->logger->event('request', 'Request started');
        });
    }

    /**
     * Public controller for recieveProjects with JSON response
     *
     * @since 1.0.0
     */
    public function receive()
    {
        return $this->getResponse(function () {
            $this->logger->event('recieve', 'Recieve started');
            $this->receiveProjects();
            $this->logger->event('recieve', 'Recieve started');
        });
    }

    /**
     * Create JSON Response from messages
     *
     * @param callable $task
     *
     * @since 1.0.0
     */
    private function getResponse(callable $task)
    {
        $lock = $this->locker->createLock('stm-cron-task', 30, false);

        $lock->acquire();

        if ($lock->isAcquired()) {
            if ($this->scHelper->checkAccess()) {
                $task();
            } else {
                $this->logger->error('Invalid credentials', JText::_('COM_STM_INCORRECT_CREDENTIALS'));
                $this->errorMessages[] = JText::_('COM_STM_INCORRECT_CREDENTIALS');
            }
        } else {
            $this->logger->error('Cron fast use', JText::_('COM_STM_FAST_USING_CRON'));
            $this->errorMessages[] = JText::_('COM_STM_FAST_USING_CRON');
        }

        $response = [
            'successMessages' => $this->successMessages,
            'errorMessages' => $this->errorMessages
        ];

        die(new JResponseJson(
            $response,
            empty($this->errorMessages) ? 'Success' : 'Have troubles',
            !empty($this->errorMessages)
        ));
    }

    /**
     * @param \Exception $e
     * @param null|stdClass $project
     * @param bool $isExport
     *
     * @return bool If project was saved
     * @since 1.0.0
     */
    private function exceptionCatcher($e, $project = null, $isExport = false)
    {
        $projectInfo = "";
        $data = [];
        $saved = false;

        if (!is_null($project)) {
            $projectInfo = " project: " . json_encode($project);

            $data = [
                'id' => $project->id,
                'entity_id' => $project->entity_id,
                'profile_id' => $project->profile_id,
                'status' => $project->status,
                'document_id' => $project->document_id,
                'task_id' => $project->task_id,
                'target_lang' => $project->target_lang,
            ];
        }

        $message = "Error: {$e->getMessage()}{$projectInfo}";

        if ($e instanceof \Http\Client\Exception\HttpException) {
            $message = "Smartcat API error: {$e->getResponse()->getBody()->getContents()}{$projectInfo}";

            if ($e instanceof \Http\Client\Common\Exception\ClientErrorException && !is_null($project)) {
                if ($isExport) {
                    $data['status'] = STMModelProject::STATUS_COMPLETED;
                } else {
                    $data['status'] = STMModelProject::STATUS_FAILED;
                }

                $saved = $this->projectModel->save($data);
            }
        }

        $message = str_replace('"', "'", $message);

        JLog::add($message, JLog::ERROR, 'jerror');

        $this->errorMessages[] = $message;
        $this->logger->error($e->getMessage(), $message);

        return $saved;
    }
}
