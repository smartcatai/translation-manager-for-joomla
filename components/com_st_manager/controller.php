<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

use Joomla\Registry\Registry;
use \Symfony\Component\Lock\Factory;

// no direct access
defined('_JEXEC') or die('Restricted access');

class STMController extends JControllerLegacy
{
    const FIELDS_EXCEPT = [
        'images' => ['image_intro', 'image_fulltext'],
        'urls' => [
            'urla',
            'urlb',
            'urlc',
        ],
    ];
    private $fileHelper;
    private $projectHelper;
    private $scHelper;
    private $exportHelper;

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
        $fieldNames = ['title', 'articletext', 'images', 'urls'];
        $documents = [];
        $projectNames = [];

        $projects = $this->projectsModel->getByStatus(STMModelProject::STATUS_WAITING);

        if (empty($projects)) {
            $this->logger->event('send', 'No projects to send');
            $this->successMessages[] = 'No projects to send';
            return;
        }

        foreach ($projects as $index => $project) {
            $project->source_lang = LanguageDictionary::getScCodeByCode($project->source_lang);
            $project->target_lang = LanguageDictionary::getScCodeByCode($project->target_lang);
            $item = $this->articleModel->getItem($project->entity_id);
            $fields = [];

            foreach ($fieldNames as $name) {
                $value = $item->$name;
                if (array_key_exists($name, self::FIELDS_EXCEPT)) {
                    if (is_array($value)) {
                        foreach (self::FIELDS_EXCEPT[$name] as $field_except) {
                            unset($value[$field_except]);
                        }
                    }
                }
                $fields[$name] = $value;
            }

            $fileName = "{$item->title}_{$project->target_lang}.json";
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
                'target_lang' => LanguageDictionary::codeConvertToJoomla($scDocument->getTargetLanguage()),
            ];

            $this->projectModel->save($data);
        }

        $this->logger->event('check', sprintf("Sent projects: %d", count($scDocuments)));
        $this->successMessages[] = sprintf("Sent projects: %d", count($scDocuments));
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

        $this->logger->event('check', sprintf("Updated projects: %d", count($updatedProjects)));
        $this->successMessages[] = sprintf("Updated projects: %d", count($updatedProjects));
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

        $this->logger->event('request', sprintf("Requested projects: %d", count($requestedProjects)));
        $this->successMessages[] = sprintf("Requested projects: %d", count($requestedProjects));
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
                    if ($field === 'articletext') {
                        $field = 'introtext';
                    }

                    if ($field === 'images' || $field === 'urls') {
                        $source_value = json_decode($article->$field, true);
                        if (array_key_exists($field, self::FIELDS_EXCEPT)) {
                            foreach (self::FIELDS_EXCEPT[$field] as $field_except) {
                                $value[$field_except] = $source_value[$field_except];
                            }
                        }
                        $value = (string) new Registry($value);
                    }

                    $article->$field = $value;
                }

                $article->alias = $article->alias . '-' . $article->language;

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

        $this->logger->event('recieve', sprintf("Received projects: %d", count($documentsDownloadSuccess)));
        $this->successMessages[] = sprintf("Received projects: %d", count($documentsDownloadSuccess));
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
        $params = JComponentHelper::getParams('com_st_manager');

        if (time() - intval($params->get('last_cron_start')) > 30) {
            $this->updateLastCronStart();

            if ($this->scHelper->checkAccess()) {
                $task();
            } else {
                $this->logger->error('Invalid credentials', JText::_('COM_STM_INCORRECT_CREDENTIALS'));
                $this->errorMessages[] = JText::_('COM_STM_INCORRECT_CREDENTIALS');
            }
        } else {
            $this->logger->error('Cron start minimal interval exceeded', JText::_('COM_STM_FAST_USING_CRON'));
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

    private function updateLastCronStart()
    {
        $component = JComponentHelper::getComponent('com_st_manager');
        $params = $component->getParams();

        $params->set('last_cron_start', time());

        $table = JTable::getInstance('extension');
        $table->load($component->id);
        $table->bind(array('params' => $params->toString()));

        // Save to database
        if (!$table->check() || !$table->store()) {
            return false;
        }

        return true;
    }
}
