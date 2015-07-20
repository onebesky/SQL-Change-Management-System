<?php

namespace common\components {

    use yii\helpers\VarDumper;
    use yii\helpers\Json;
    use Yii;

    /**
     * Customized log/debug component.
     * Usage: Logger::d($variable, 'string', $object);
     *
     * @author Ondrej Nebesky <ondrej@freshrealm.co>
     */
    class Logger extends \yii\base\Component {

        /**
         * Assoc array of files and logs.
         * @var type
         */
        private $openedFiles;

        /**
         * Disable logging
         * @var type
         */
        public $enabled = true;

        /**
         * Store all the log files by day. Creates a folder for each day automatically.
         * @var type
         */
        public $storeByDate = true;

        /**
         * Shorten your path by putting in your first few folders here. Whatever you enter
         * here will be replaced with "...". Eg: enter /Users/aaron/Applications/MAMP/ to
         * have that replaced with "..." in the logs.
         * @var string
         */
        public $trimPath = "";

        /**
         * Filename without .log extension
         * @var type
         */
        public $defaultFile = 'debug';
        private $yesterdayTimestamp;

        /**
         * Time in given timezone
         * @var DateTime
         */
        private $time = null;

        /**
         * Write debug messages on screen
         * @var type
         */
        public $enableJavaScriptLog = false;

        /**
         * Remove old files from filesystem rather than keeping them. Set
         * as a number of minutes of the oldes acceptible log.
         * @var type
         */
        public $automaticDelete = false;

        /**
         * Internal timestamp for profiling
         * @var timestamp
         */
        private $_starttime;

        /**
         * Internal to keep track of the last marked point for profiling
         * @var timestamp
         */
        private $_last = 0;

        /**
         * Internal to keep track of DB queries
         * @var
         */
        private $_lastDbCount;

        /**
         * Log timestamp will be translated into this timezone
         * @var type
         */
        public $timezone = "America/Los_Angeles";
        public $logDir;

        public function init() {
            //$this->attachEventHandler('onEndRequest', array($this, 'flush'));
            $serverTz = new \DateTimeZone($this->timezone);
            $now = new \DateTime();
            $now->setTimezone($serverTz);
            $now->setTime(0, 0);
            $this->yesterdayTimestamp = $now->getTimestamp();
            $this->time = new \DateTime();
            $this->time->setTimezone($serverTz);

            if ($this->logDir == null) {
                $this->logDir = Yii::getAlias('@app/runtime/logs');
            }

            if (!is_dir($this->logDir)) {
                mkdir($this->logDir, 0777, true);
            }
        }

        public function debugInternal($variables, $trace, $logFile = null) {
            if (!$this->enabled) {
                return;
            }

            if ($logFile == null) {
                $logFile = $this->defaultFile;
            }

            $line = $trace[0]['line'];
            $pos = strpos($trace[0]['file'], 'protected');
            $file = substr($trace[0]['file'], $pos);

            $file = str_replace($this->trimPath, "...", $file);

            $output = $this->formatOutput($file, $line, $variables);

            $handle = $this->getHandle($logFile);

            fwrite($handle, $output);
            if ($this->enableJavaScriptLog) {
                $this->formatJs($file, $line, $variables);
            }
        }

        /**
         * Debug to file
         */
        public function debug() {
            // data we are going to log
            $variables = func_get_args();
            $trace = debug_backtrace(null, 1);

            $this->debugInternal($variables, $trace);
        }

        /**
         * Shortcut for debug function
         */
        public function log() {
            // data we are going to log
            $variables = func_get_args();
            $trace = debug_backtrace(null, 1);
            Yii::info(VarDumper::dump($this));
            $this->debugInternal($variables, $trace);
        }

        /**
         * Shortcut for debug function
         */
        public function d() {
            // data we are going to log
            $variables = func_get_args();
            $trace = debug_backtrace(null, 1);

            $this->debugInternal($variables, $trace);
        }

        /**
         * Write output to specific log file
         */
        public function debugToFile() {
            // data we are going to log
            $variables = func_get_args();
            $logFile = $variables[0];
            $trace = debug_backtrace(null, 1);
            $this->debugInternal(array_slice($variables, 1), $trace, $logFile);
        }

        private function formatOutput($file, $line, $variables) {
            $this->time->setTimestamp(time());
            $time = $this->time->format('Y-m-d H:i:s');
            $output = "$time: $file ($line): ";
            for ($i = 0; $i < count($variables); $i++) {
                if ($i > 0) {
                    $output .= ', ';
                }
                $level = is_a($variables[$i], 'ActiveRecord') ? 2 : 5;
                $output .= VarDumper::dumpAsString($variables[$i], $level);
            }
            return $output . "\r\n";
        }

        private function getHandle($file) {

            if (isset($this->openedFiles[$file])) {
                return $this->openedFiles[$file];
            }
            //$path = YiiBase::getPathOfAlias('application.runtime');
            $path = $this->logDir;
            if ($this->storeByDate) {
                $dir = $path . '/' . date('Y-m-d', $this->yesterdayTimestamp - 1);
                if (!file_exists($dir)) {
                    mkdir($dir);
                }
                $debugFile = $dir . '/' . $file . '.log';
            } else {
                $debugFile = $path . "/$file.log";
            }

            // don't preserve content of the file if we need to delete it
            $this->openedFiles[$file] = fopen($debugFile, $this->automaticDelete ? 'w' : 'a');
            return $this->openedFiles[$file];
        }

        public function flush() {
            //d("Flush All the files");
            foreach ($this->openedFiles as $handle) {
                fclose($handle);
            }
        }

        private function formatJs($file, $line, $variables) {
            if (!isset(app()->clientScript)) {
                return;
            }
            $output = array();

            for ($i = 0; $i < count($variables); $i++) {

                $output[] = array(
                    'file' => $file,
                    'line' => $line,
                    'message' =>
                    VarDumper::dumpAsString($variables[$i])
                );
            }
            cs()->registerScript('js-debug', '
            console.log(' . Json::encode($output) . ');
                ');
        }

        private function startTimer(){
            $mtime = microtime(true);
            $this->debug($mtime);
            $this->_starttime = $mtime;
            $this->_last = $mtime;
            $bt = debug_backtrace();
            // pass through the original caller, not the this->mark() call.
            array_shift($bt);
            $this->debugInternal(['Timer started'], $bt);
        }

        public function mark($label = ''){
            if(!$this->_starttime) $this->startTimer();
            $mtime = microtime(true);
            $endtime = $mtime;
            $totaltime = $endtime - $this->_starttime;
            $last = $endtime - $this->_last;
            $bt = debug_backtrace();
            if(strlen($label)) $label .= ': ';
            $last = round($last, 5);
            $totaltime = round($totaltime, 5);
            if($last < .0001) $last = "0.0000";
            if($totaltime < .0001) $totaltime = "0.0000";
            $this->debugInternal([$label.'Increment: ' . $last . "(s) / Total: " . $totaltime . "(s)"], $bt);
            $this->_last = $endtime;
        }

        public function queryCount($label = ''){
            $count = Yii::getLogger()->dbProfiling[0];
            $incremental = $count - $this->_lastDbCount;
            $this->_lastDbCount = $count;
            if($label) $label .= ": ";
            $this->debugInternal([$label . $incremental . "/" . $count . " (incremental/total)"], debug_backtrace());
        }
    }
}

// declare global debug function

namespace {

    function d() {
        // data we are going to log
        $variables = func_get_args();
        $trace = debug_backtrace(null, 1);
        \Yii::$app->logger->debugInternal($variables, $trace);
    }

}