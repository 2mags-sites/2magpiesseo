<?php
/**
 * Data Loader - Single source of truth for all business data
 * This ensures we NEVER hallucinate information
 * All content must come from documented sources
 */

class DataLoader {
    private static $instance = null;
    private $business_data;
    private $seo_data;
    private $content_data;
    
    private function __construct() {
        // Load all JSON data files
        $base_path = __DIR__ . '/../../';
        
        // Business information
        $this->business_data = $this->loadJSON($base_path . '01-scraping/business-info.json');
        
        // SEO keywords and meta
        $this->seo_data = $this->loadJSON($base_path . '03-seo/keyword-mapping.json');
        
        // Content requirements if exists
        if (file_exists($base_path . '04-content/content-requirements.json')) {
            $this->content_data = $this->loadJSON($base_path . '04-content/content-requirements.json');
        }
    }
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DataLoader();
        }
        return self::$instance;
    }
    
    private function loadJSON($filepath) {
        if (!file_exists($filepath)) {
            throw new Exception("Critical data file missing: " . $filepath . "\nCannot proceed without source data.");
        }
        return json_decode(file_get_contents($filepath), true);
    }
    
    /**
     * Get business information
     */
    public function getBusinessInfo() {
        return $this->business_data;
    }
    
    /**
     * Get contact information
     */
    public function getContact() {
        return $this->business_data['contact'];
    }
    
    /**
     * Get address
     */
    public function getAddress() {
        return $this->business_data['address'];
    }
    
    /**
     * Get services list
     */
    public function getServices() {
        return $this->business_data['services'];
    }
    
    /**
     * Get specific service by name
     */
    public function getService($name) {
        foreach ($this->business_data['services'] as $service) {
            if (stripos($service['name'], $name) !== false) {
                return $service;
            }
        }
        return null;
    }
    
    /**
     * Get service areas
     */
    public function getServiceAreas() {
        return $this->business_data['service_areas'];
    }
    
    /**
     * Get SEO data for a specific page
     */
    public function getPageSEO($page_name) {
        if (isset($this->seo_data['pages'][$page_name])) {
            return $this->seo_data['pages'][$page_name];
        }
        // Return defaults if page not found
        return [
            'title' => $this->business_data['business_name'],
            'primary_keywords' => [],
            'secondary_keywords' => [],
            'long_tail' => []
        ];
    }
    
    /**
     * Get USPs
     */
    public function getUSPs() {
        return $this->business_data['unique_selling_points'];
    }
    
    /**
     * Get opening hours
     */
    public function getOpeningHours() {
        return $this->business_data['opening_hours'];
    }
    
    /**
     * Check if we have data for something
     * Use this to avoid generating content we don't have
     */
    public function hasData($key) {
        return isset($this->business_data[$key]) && !empty($this->business_data[$key]);
    }
    
    /**
     * Get tone guidelines
     * This would ideally come from a tone analysis file
     */
    public function getToneGuidelines() {
        // These should be documented from actual site analysis
        return [
            'style' => 'compassionate, professional, respectful',
            'avoid' => ['cheap', 'deal', 'discount', 'dead' as adjective'],
            'use' => ['loved one', 'pass away', 'with dignity', 'compassionate support']
        ];
    }
}

// Helper function for easy access
function getData() {
    return DataLoader::getInstance();
}

// Example usage in PHP pages:
// $data = getData();
// $contact = $data->getContact();
// $services = $data->getServices();
// NEVER hardcode business information!
?>