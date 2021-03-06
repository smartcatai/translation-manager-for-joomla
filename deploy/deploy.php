<?php
/**
 * @package    Smartcat Translation Manager
 *
 * @author     Smartcat <support@smartcat.ai>
 * @copyright  (c) 2019 Smartcat. All Rights Reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       http://smartcat.ai
 */

$basePath = __DIR__;
$gitPath = dirname($basePath);

$components = ['com_st_manager', 'plg_stm_check_login', 'plg_stm_send_to_translate', 'lib_smartcat_api'];

if ($basePath == "$gitPath/deploy") {
    exec("rm -rf '$gitPath/build/'*");
    exec("cp -R '$gitPath/deploy/'* '$gitPath/build/'");
    $basePath = "$gitPath/build";
}

$pkgPath = $basePath . '/pkg_st_manager';

echo("Preparing..." . PHP_EOL);

// Create folders
exec("mkdir -p '$gitPath/build/com_st_manager/administrator/components'");
exec("mkdir -p '$gitPath/build/com_st_manager/components'");
exec("mkdir -p '$gitPath/build/lib_smartcat_api/vendor'");
exec("mkdir -p '$gitPath/build/pkg_st_manager/language'");
exec("mkdir -p '$gitPath/build/plg_stm_check_login/language'");
exec("mkdir -p '$gitPath/build/plg_stm_send_to_translate/language'");

// Copy files
exec("cp -R '$gitPath/administrator/components/com_st_manager' '$gitPath/build/com_st_manager/administrator/components/'");
exec("cp -R '$gitPath/components/com_st_manager' '$gitPath/build/com_st_manager/components/'");
exec("mv '$gitPath/build/com_st_manager/administrator/components/com_st_manager/com_st_manager.xml' '$gitPath/build/com_st_manager/com_st_manager.xml'");
exec("cp -R '$gitPath/plugins/extension/stm_check_login/'* '$gitPath/build/plg_stm_check_login/'");
exec("cp -R '$gitPath/plugins/system/stm_send_to_translate/'* '$gitPath/build/plg_stm_send_to_translate/'");
exec("cp -R '$gitPath/libraries/smartcat_api/'* '$gitPath/build/lib_smartcat_api/vendor'");
exec("cp '$gitPath/administrator/manifests/libraries/smartcat_api.xml' '$gitPath/build/lib_smartcat_api/lib_smartcat_api.xml'");
exec("cp '$gitPath/administrator/manifests/packages/pkg_st_manager.xml' '$gitPath/build/pkg_st_manager/pkg_st_manager.xml'");
exec("cp '$gitPath/license.txt' '$gitPath/build/pkg_st_manager/license.txt'");
exec("cp '$gitPath/license.txt' '$gitPath/build/com_st_manager/license.txt'");
exec("cp '$gitPath/license.txt' '$gitPath/build/lib_smartcat_api/license.txt'");
exec("cp '$gitPath/license.txt' '$gitPath/build/plg_stm_check_login/license.txt'");
exec("cp '$gitPath/license.txt' '$gitPath/build/plg_stm_send_to_translate/license.txt'");

// Copy languages
exec("cp '$gitPath/administrator/language/en-GB/'*stm_send_to_translate* '$gitPath/build/plg_stm_send_to_translate/language'");
exec("cp '$gitPath/administrator/language/en-GB/'*stm_check_login* '$gitPath/build/plg_stm_check_login/language'");
exec("cp '$gitPath/administrator/language/ru-RU/'*stm_send_to_translate* '$gitPath/build/plg_stm_send_to_translate/language'");
exec("cp '$gitPath/administrator/language/ru-RU/'*stm_check_login* '$gitPath/build/plg_stm_check_login/language'");
exec("cp '$gitPath/language/en-GB/'*pkg_st_manager* '$gitPath/build/pkg_st_manager/language'");

//Delete test directories
exec("rm -rf '$gitPath/build/lib_smartcat_api/vendor/clue/stream-filter/tests'");
exec("rm -rf '$gitPath/build/lib_smartcat_api/vendor/clue/stream-filter/examples'");
exec("rm -rf '$gitPath/build/lib_smartcat_api/vendor/ralouphie/getallheaders/tests'");
exec("rm -rf '$gitPath/build/lib_smartcat_api/vendor/symfony/lock/Tests'");
exec("rm -rf '$gitPath/build/lib_smartcat_api/vendor/symfony/options-resolver/Tests'");
exec("rm -rf '$gitPath/build/lib_smartcat_api/vendor/symfony/serializer/Tests'");

//Add custom header to all composer php files
echo("Adding headers" . PHP_EOL);
$files = Filesystem::globRecursive("$gitPath/build/lib_smartcat_api/*.php");
foreach ($files as $file) {
    Filesystem::prependToFile($file, "$gitPath/build/headers");
}

@unlink($basePath . '/pkg_st_manager.zip');

foreach ($components as $component) {
    @unlink("{$pkgPath}/{$component}.zip");
    echo("Process {$component} component" . PHP_EOL);
    Filesystem::zipDir("{$basePath}/{$component}", "{$pkgPath}/{$component}.zip", true);
}

echo("Process full package" . PHP_EOL);
Filesystem::zipDir($pkgPath, $basePath . '/pkg_st_manager.zip', true);

class Filesystem
{
    /**
     * Add files and sub-directories in a folder to zip file.
     * @param string $folder
     * @param ZipArchive $zipFile
     * @param int $exclusiveLength Number of text to be exclusived from the file path.
     */
    private static function folderToZip($folder, &$zipFile, $exclusiveLength) {
        $handle = opendir($folder);
        if ($handle === false) {
            die("Can't open '$folder'" . PHP_EOL);
        }
        while (false !== $f = readdir($handle)) {
            if ($f != '.' && $f != '..') {
                $filePath = "$folder/$f";
                // Remove prefix from file path before add to zip.
                $localPath = substr($filePath, $exclusiveLength);
                if (is_file($filePath)) {
                    $zipFile->addFile($filePath, $localPath);
                } elseif (is_dir($filePath)) {
                    // Add sub-directory.
                    $zipFile->addEmptyDir($localPath);
                    self::folderToZip($filePath, $zipFile, $exclusiveLength);
                }
            }
        }
        closedir($handle);
    }

    /**
     * Zip a folder (include itself).
     * Usage:
     *   HZip::zipDir('/path/to/sourceDir', '/path/to/out.zip');
     *
     * @param string $sourcePath Path of directory to be zip.
     * @param string $outZipPath Path of output zip file.
     * @param bool $onlyIncluded Including only files in folder
     */
    public static function zipDir($sourcePath, $outZipPath, $onlyIncluded = false)
    {
        $pathInfo = pathInfo($sourcePath);
        $parentPath = $pathInfo['dirname'];
        $dirName = $pathInfo['basename'];

        $z = new ZipArchive();
        $z->open($outZipPath, ZIPARCHIVE::CREATE);
        if (!$onlyIncluded) {
            $z->addEmptyDir($dirName);
        } else {
            $parentPath = $sourcePath;
        }
        self::folderToZip($sourcePath, $z, strlen("$parentPath/"));
        $z->close();
    }

    public static function globRecursive($pattern, $flags = 0, $exclude = [])
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
            if ( in_array( $dir, $exclude ) ) {
                continue;
            }
            $files = array_merge($files, self::globRecursive($dir.'/'.basename($pattern), $flags, $exclude));
        }
        return $files;
    }

    public static function prependToFile($fileToPrepend, $prependFile) {
        $data = file_get_contents($fileToPrepend);
        $header = file_get_contents($prependFile);
        $count = 0;

        $restrict = "// no direct access\ndefined('_JEXEC') or die('Restricted access');";
        $data = preg_replace('%^\s*(namespace .*)$\s*%m', "$1\n\n$restrict\n\n", $data, -1, $count);

        if ($count === 0) {
            $header .= "\n$restrict\n";
        }

        $data = str_replace('<?php', $header, $data);

        file_put_contents($fileToPrepend, $data);
    }
}
