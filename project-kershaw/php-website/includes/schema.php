<?php
/**
 * Schema.org Structured Data Generator
 * References business data from project documentation
 */

// Load business data from documentation
$business_data_file = __DIR__ . '/../../project-kershaw/01-scraping/business-info.json';
$business_data = json_decode(file_get_contents($business_data_file), true);

// Load SEO data if needed
$seo_data_file = __DIR__ . '/../../project-kershaw/03-seo/keyword-mapping.json';
$seo_data = json_decode(file_get_contents($seo_data_file), true);

/**
 * Generate LocalBusiness Schema
 */
function getLocalBusinessSchema($business_data) {
    return [
        "@context" => "https://schema.org",
        "@type" => "FuneralHome",
        "name" => $business_data['business_name'],
        "image" => "https://www.arthurkershawfunerals.com/assets/images/logo.png",
        "@id" => $business_data['contact']['website'],
        "url" => $business_data['contact']['website'],
        "telephone" => $business_data['contact']['phone_primary'],
        "priceRange" => "£" . min(array_column(array_filter($business_data['services'], function($s) { return $s['price_from'] !== null; }), 'price_from')) . " - £5000",
        "address" => [
            "@type" => "PostalAddress",
            "streetAddress" => $business_data['address']['street'],
            "addressLocality" => $business_data['address']['city'],
            "addressRegion" => $business_data['address']['county'],
            "postalCode" => $business_data['address']['postcode'],
            "addressCountry" => "GB"
        ],
        "geo" => [
            "@type" => "GeoCoordinates",
            "latitude" => $business_data['address']['coordinates']['latitude'],
            "longitude" => $business_data['address']['coordinates']['longitude']
        ],
        "openingHoursSpecification" => [
            [
                "@type" => "OpeningHoursSpecification",
                "dayOfWeek" => ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
                "opens" => "09:00",
                "closes" => "17:00"
            ],
            [
                "@type" => "OpeningHoursSpecification",
                "dayOfWeek" => "Saturday",
                "opens" => "09:00",
                "closes" => "12:00"
            ]
        ],
        "hasOfferCatalog" => [
            "@type" => "OfferCatalog",
            "name" => "Funeral Services",
            "itemListElement" => array_map(function($service) {
                return [
                    "@type" => "Offer",
                    "itemOffered" => [
                        "@type" => "Service",
                        "name" => $service['name'],
                        "description" => $service['description']
                    ],
                    "price" => $service['price_from'],
                    "priceCurrency" => "GBP"
                ];
            }, array_filter($business_data['services'], function($s) { return $s['price_from'] !== null; }))
        ]
    ];
}

/**
 * Generate Service Schema
 */
function getServiceSchema($service_name, $description, $price_from = null) {
    global $business_data;
    
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "Service",
        "serviceType" => $service_name,
        "provider" => [
            "@type" => "FuneralHome",
            "name" => $business_data['business_name'],
            "telephone" => $business_data['contact']['phone_primary'],
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => $business_data['address']['street'],
                "addressLocality" => $business_data['address']['city'],
                "postalCode" => $business_data['address']['postcode']
            ]
        ],
        "description" => $description,
        "areaServed" => array_map(function($area) {
            return [
                "@type" => "City",
                "name" => $area['name']
            ];
        }, $business_data['service_areas'])
    ];
    
    if ($price_from !== null) {
        $schema['offers'] = [
            "@type" => "Offer",
            "price" => $price_from,
            "priceCurrency" => "GBP"
        ];
    }
    
    return $schema;
}

/**
 * Generate FAQ Schema
 */
function getFAQSchema($faqs) {
    return [
        "@context" => "https://schema.org",
        "@type" => "FAQPage",
        "mainEntity" => array_map(function($faq) {
            return [
                "@type" => "Question",
                "name" => $faq['question'],
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => $faq['answer']
                ]
            ];
        }, $faqs)
    ];
}

/**
 * Output Schema as JSON-LD
 */
function outputSchema($schema) {
    echo '<script type="application/ld+json">' . "\n";
    echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    echo "\n" . '</script>' . "\n";
}
?>