<?php
class ExportSTATAxml extends \ls\pluginmanager\PluginBase {
    
    protected $storage = 'DbStorage';
       
    static protected $description = 'Core: Export survey results to a STATA xml file';
    static protected $name = 'STATA Export';
    
    public function init() {
        
        /**
         * Here you should handle subscribing to the events your plugin will handle
         */
        $this->subscribe('listExportPlugins');
        $this->subscribe('listExportOptions');
        $this->subscribe('newExport');
    }
    
    protected $settings = array(
        'statafileversion' => array(
            'type' => 'select',
            'label' => 'Export for Stata',
            'options' => array('113' => 'version 8 through 12', '117'  => 'version 13 and up'),
            'default' => '113',
            'submitonchange'=> false
            )
        );

    public function listExportOptions()
    {
        $event = $this->getEvent();
        $type = $event->get('type');
        
        switch ($type) {
            case 'stataxml':
                $event->set('label', gT("STATA (.xml)"));
                $event->set('onclick', 'document.getElementById("ansabbrev").checked=true;
                        document.getElementById("ansfull").disabled=true;document.getElementById("convertyto1").checked=true;document.getElementById("convertnto2").checked=true;
                        document.getElementById("convertnto").value=0;document.getElementById("convertyto").value=1;
                        document.getElementById("headcodes").disabled=true;document.getElementById("headabbreviated").disabled=true;document.getElementById("headfull").checked=true;');
                break;

            default:
                break;
        }
    }   
    
    /**
     * Registers this export type
     */
    public function listExportPlugins()
    {
        $event = $this->getEvent();
        $exports = $event->get('exportplugins');
        
        // Yes we overwrite existing classes if available
        $exports['stataxml'] = get_class();
        $event->set('exportplugins', $exports);
    }
    
    /**
     * Returns the required IWriter
     */
    public function newExport()
    {
        $event = $this->getEvent();
        $type = $event->get('type');

        $pluginsettings=$this->getPluginSettings(true);
        $writer = new STATAxmlWriter($pluginsettings);
        $event->set('writer', $writer);
    }
}