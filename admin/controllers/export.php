<?php
/**
 * @package    JEM
 * @copyright  (C) 2013-2026 joomlaeventmanager.net
 * @copyright  (C) 2005-2009 Christoph Lukes
 * @license    https://www.gnu.org/licenses/gpl-3.0 GNU/GPL
 *
 * Based on: https://gist.github.com/dongilbert/4195504
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\AdminController;

/**
 * JEM Component Export Controller
 */
class JemControllerExport extends AdminController
{
    /**
     * Proxy for getModel.
     */
    public function getModel($name = 'Export', $prefix = 'JemModel', $config = [])
    {
        return parent::getModel($name, $prefix, ['ignore_request' => true]);
    }

    public function export()
    {
        $this->checkToken();
        $this->streamCsv("jem_export-" . date('Ymd-His') . ".csv", 'getCsv');
    }

    public function exportcats()
    {
        $this->checkToken();
        $this->streamCsv('categories.csv', 'getCsvcats');
    }

    public function exportvenues()
    {
        $this->checkToken();
        $this->streamCsv('venues.csv', 'getCsvvenues');
    }

    public function exportcatevents()
    {
        $this->checkToken();
        $this->streamCsv('catevents.csv', 'getCsvcatsevents');
    }

    /**
     * Stream a CSV file to the browser using the J6 Response object.
     *
     * @param  string  $filename   Download filename
     * @param  string  $modelMethod  Method on the Export model to call
     */
    private function streamCsv(string $filename, string $modelMethod): void
    {
        $app      = Factory::getApplication();
        $response = $app->getResponse();

        $response = $response
            ->withHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->withHeader('Pragma', 'no-cache')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');

        // Emit headers before streaming body
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header($name . ': ' . $value, false);
            }
        }

        // Stream CSV body directly to output buffer
        $this->getModel()->{$modelMethod}();

        $app->close();
    }
}
