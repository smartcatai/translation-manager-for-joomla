<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

class ExportTranslationHelper
{
    private $api;
    private $fileHelper;

    /**
     * current instance
     *
     * @var ExportTranslationHelper
     */
    private static $instance = null;

    /**
     * return current instance
     *
     * @return ExportTranslationHelper
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * ExportTranslationHelper constructor.
     */
    public function __construct()
    {
        $this->api = SCHelper::getInstance();
        $this->fileHelper = FileHelper::getInstance();
    }

    /**
     * @param $documentIds
     *
     * @return bool|string
     *
     * @throws \Http\Client\Exception\HttpException
     *
     * @since 1.0.0
     */
    public function requestExport($documentIds)
    {
        if (empty($documentIds)) {
            return false;
        }

        if (is_scalar($documentIds)) {
            $documentIds = [$documentIds];
        }

        $export = $this->api
            ->getDocumentExportManager()
            ->documentExportRequestExport(['documentIds' => $documentIds]);

        return $export->getId();
    }

    /**
     * @param $exportId
     *
     * @return array|bool
     *
     * @throws \Http\Client\Exception\HttpException
     * @throws Exception
     *
     * @since 1.0.0
     */
    public function downloadDocs($exportId)
    {
        if (empty($exportId)) {
            throw new \Exception('ExportId is empty');
        }

        $response = $this->api
            ->getDocumentExportManager()
            ->documentExportDownloadExportResult($exportId);

        if ($response->getStatusCode() === 204) {
            return [];
        }

        $mimeType = $response->getHeaderLine('Content-Type');

        if ($mimeType !== 'application/json') {
            throw new \Exception('Mime type is not valid: ' . $mimeType);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
