<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

use SmartCat\Client\Model\BilingualFileImportSettingsModel;
use SmartCat\Client\Model\CreateDocumentPropertyWithFilesModel;
use SmartCat\Client\Model\CreateProjectModel;
use SmartCat\Client\Model\DocumentModel;

// no direct access
defined('_JEXEC') or die('Restricted access');

class ProjectHelper
{
    /**
     * current instance
     *
     * @var ProjectHelper
     */
    private static $instance = null;

    /**
     * return current instance
     *
     * @return ProjectHelper
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->api = SCHelper::getInstance();
    }

    public function createProject($profile, $projectName = '')
    {
        $newScProject = (new CreateProjectModel())
            ->setUseMT(false)
            ->setPretranslate(false)
            ->setAssignToVendor(false);

        if ((bool) $profile->vendor) {
            $newScProject
                ->setAssignToVendor(true)
                ->setVendorAccountIds([$profile->vendor]);
        }

        $name = !empty($projectName) ? $projectName : $profile->name;
        $newScProject
            ->setName(self::filterChars($name))
            ->setDescription(isset($profile->description) ? $profile->description : 'Content from joomla')
            ->setSourceLanguage($profile->source_lang)
            ->setTargetLanguages(is_array($profile->target_lang) ? $profile->target_lang : explode(',', $profile->target_lang))
            ->setWorkflowStages(is_array($profile->stages) ? $profile->stages : explode(',', $profile->stages))
            ->setExternalTag('source:Joomla');

        return $this->api
            ->getProjectManager()
            ->projectCreateProject($newScProject);
    }

    /**
     * @return \SmartCat\Client\Model\BilingualFileImportSettingsModel
     */
    public function getFileImportSettings()
    {
        return (new BilingualFileImportSettingsModel())
            ->setConfirmMode('none')
            ->setLockMode('none')
            ->setTargetSubstitutionMode('all');
    }

    /**
     * Create and return document property with file model.
     *
     * @param mixed $filePath
     * @param string $fileName
     * @param int $externalId
     * @param $targetLang
     *
     * @return \SmartCat\Client\Model\CreateDocumentPropertyWithFilesModel
     */
    public function createDocumentFromFile($filePath, $fileName, $externalId, $targetLang)
    {
        $documentModel = new CreateDocumentPropertyWithFilesModel();
        $documentModel->setBilingualFileImportSettings($this->getFileImportSettings());
        $documentModel->setTargetLanguages([$targetLang]);
        $documentModel->setExternalId($externalId);
        $documentModel->attachFile($filePath, $fileName);

        return $documentModel;
    }

    /**
     * @param CreateDocumentPropertyWithFilesModel[] $documents
     * @param string $externalProjectId
     *
     * @return DocumentModel[]
     *
     * @since version
     */
    public function sendDocuments($documents, $externalProjectId)
    {
        $resDocuments = [];
        $project = $this->api->getProjectManager()->projectGet($externalProjectId);

        $scDocuments = array_map(
            function (DocumentModel $value) {
                return $value->getName() . '.json';
            },
            $project->getDocuments()
        );

        foreach ($documents as $document) {
            $index = array_search($document->getFile()['fileName'], $scDocuments, true);

            if (false !== $index) {
                $resDocuments[] = $this->api->getDocumentManager()->documentUpdate([
                    'documentId'   => $scDocuments[$index]->getId(),
                    'uploadedFile' => $document->getFile(),
                ]);
            } else {
                $resDocuments[] = $this->api->getProjectManager()->projectAddDocument([
                    'documentModel' => [$document],
                    'projectId' => $externalProjectId,
                ]);
            }
        }

        return $resDocuments;
    }


    public static function filterChars($s)
    {
        return mb_substr(str_replace(['*', '|', '\\', ':', '"', '<', '>', '?', '/'], '_', $s), 0, 94);
    }
}
