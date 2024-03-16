<?php

namespace Elementor;

class All_Blog_Post extends Widget_Base
{
    public function get_name()
    {
        return 'boomdevs-check-page';
    }

    public function get_title()
    {
        return __('Boomdevs Check Site', '');
    }

    public function get_icon()
    {
        return 'eicon-testimonial';
    }

    public function get_categories()
    {
        return ['basic'];
    }

    protected function register_controls()
    {
    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();
        $url = isset($_GET['url']) ? urldecode($_GET['url']) : '';


        if (isset($_POST['inputValue'])) {
            $inputValueFromJS = $_POST['inputValue'];
        
            // Now you can use $inputValueFromJS in your existing PHP code
            // For example, you can update the $url variable with the new value:
            $url2 = $inputValueFromJS;
            var_dump($url2);
        }

        if (isset($_GET['url'])) {
            $url = urldecode($_GET['url']);
            $api_url = 'https://api.wpsafe.ai/api/v1/healthcheck/?site_url=' . urlencode($url);

                // Make an API request using wp_remote_get
                $response = wp_remote_get($api_url);

            if (!is_wp_error($response) && $response['response']['code'] === 200) {
                // Handle the API response here
				$api_data = json_decode($response['body'], true);
                echo "<script>";
                echo "console.log('API Data:', " . json_encode($api_data) . ");";
                echo "</script>";

				if ($api_data !== null) {
                    
                    $anomalyMsg = '';
                    if (isset($api_data['warnings']['site_issue'][0]['msg'])) {
                        $anomalyMsg = $api_data['warnings']['site_issue'][0]['msg'];
                    }

                    $ipAddresses = '';
                    $firstIPAddress = '';
                    if (isset($api_data['site']['ip']) && is_array($api_data['site']['ip'])) {
                        $ipAddresses = $api_data['site']['ip'];
                        $firstIPAddress = isset($ipAddresses[0]) ? $ipAddresses[0] : '';
                    }

                    $cdn = '';
                    if (isset($api_data['site']['cdn']) && is_array($api_data['site']['cdn'])) {
                        $cdnAddresses = $api_data['site']['cdn'];
                        $cdn = isset($cdnAddresses[0]) ? $cdnAddresses[0] : '';
                    }

                    $powered_by = '';
                    if (isset($api_data['site']['powered_by']) && is_array($api_data['site']['powered_by'])) {
                        $poweredByArray = $api_data['site']['powered_by'];
                        $powered_by = isset($poweredByArray[0]) ? $poweredByArray[0] : '';
                    }

                    $running_on = '';
                    if (isset($api_data['site']['running_on']) && is_array($api_data['site']['running_on'])) {
                        $runningAddresses = $api_data['site']['running_on'];
                        $running_on = isset($runningAddresses[0]) ? $runningAddresses[0] : '';
                    }

                    $firstCMS = '';
                    if (isset($api_data['software']['cms']) && is_array($api_data['software']['cms'])) {
                        $cmsArray = $api_data['software']['cms'];
                        $firstCMS = isset($cmsArray[0]['name']) ? $cmsArray[0]['name'] : '';
                    }
                    $certExpires = '';
                    if (isset($api_data['tls']['cert_expires'])) {
                        $certExpires = $api_data['tls']['cert_expires'];
                    }

                    $cert_issuer = '';
                    if ($api_data !== null && isset($api_data['tls']['cert_issuer'])) {
                        $cert_issuer = $api_data['tls']['cert_issuer'];
                    }

                    $js_external = '';
                    if ($api_data !== null && isset($api_data['links']['js_external'])) {
                        $js_external = $api_data['links']['js_external'];
                    }
                    $js_local = '';
                    if ($api_data !== null && isset($api_data['links']['js_local'])) {
                        $js_local = $api_data['links']['js_local'];
                    }
                    $totalRating = isset($api_data['ratings']['total']['rating']) ? $api_data['ratings']['total']['rating'] : '';
                    ?>
                        <div class="site-scan-result" id="site-scan-result">
                            <div class="container">
                                <h3 class="site-scan-result-title"><a href="/sitecheck"><span class="dashicons dashicons-arrow-left-alt"> </span></a>Your Site Result</h3>
                                <div class="scan-result-wrapper">
                                    <div class="result-heading">
                                        <div class="heading-content-inner">
                                            <?php
                                                if($anomalyMsg != '' ){
                                                    ?>
                                                        <div class="heading-content">
                                                            <div class="scan-icon error">
                                                                <span class="dashicons dashicons-warning"></span>   
                                                            </div>
                                                            <div class="content">
                                                                <h3 class="title">Site Issue</h3>
                                                                <p class="desc"><?php echo $anomalyMsg; ?></p>
                                                            </div>
                                                        </div>
                                                    <?php
                                                }else{
                                                    ?>
                                                        <div class="heading-content">
                                                            <div class="scan-icon safe">
                                                                <span class="dashicons dashicons-saved"></span>   
                                                            </div>
                                                            <div class="content">
                                                                <h3 class="title">No Malware Found</h3>
                                                                <p class="desc">Our scanner didn't detect any malware</p>
                                                            </div>
                                                        </div>
                                                    <?php
                                                }
                                            ?>
                                            <div class="heading-content">
                                                <div class="scan-icon safe">
                                                    <span class="dashicons dashicons-saved"></span>
                                                </div>
                                                <div class="content">
                                                    <h3 class="title">Site is not Blacklisted</h3>
                                                    <p class="desc">9 Blacklists checked</p>
                                                </div>
                                            </div>
                                            <?php
                                                if($anomalyMsg != '' ){
                                                    ?>
                                                        <div>
                                                            <a href="#" class="result-heading-button">Request Review</a>
                                                        </div>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="result-box">
                                            <div class="result-url">
                                                <div class="result-url-content">
                                                    <h3 class="title"><span class="dashicons dashicons-admin-links"></span> Redirects to:</h3>
                                                    <p class="url"><?php echo $api_data['site']['final_url']; ?></p>
                                                </div>
                                            </div>
                                            <div class="result-all">
                                                <div class="result-content">
                                                    <?php
                                                        if($firstIPAddress != ''){
                                                            ?>
                                                                <div class="single-content">
                                                                    <span class="label">IP address:</span>
                                                                    <span class="label-data"><?php echo $firstIPAddress; ?></span>
                                                                </div>
                                                            <?php
                                                        }
                                                        if($cdn != ''){
                                                            ?>
                                                                <div class="single-content">
                                                                    <span class="label">CDN: </span>
                                                                    <span class="label-data"><?php echo $cdn; ?></span>
                                                                </div>
                                                            <?php
                                                        }
                                                        if($running_on != ''){
                                                            ?>
                                                                <div class="single-content">
                                                                    <span class="label">Running on:</span>
                                                                    <span class="label-data"><?php echo $running_on; ?></span>
                                                                </div>
                                                            <?php
                                                        }
                                                    ?>
                                                </div>
                                                <div class="result-content">
                                                    <?php
                                                        if($firstCMS != ''){
                                                            ?>
                                                                <div class="single-content">
                                                                    <span class="label">CMS:</span>
                                                                    <span class="label-data"><?php echo $firstCMS; ?></span>
                                                                </div>
                                                            <?php
                                                        }
                                                    ?>
                                                    <div class="single-content">
                                                        <span class="label">Powered by:</span>
                                                        <span class="label-data"><?php if($powered_by != ''){ echo $powered_by;}else{ echo "Unknown";} ?></span>
                                                    </div>
                                                </div>   
                                            </div>
                                            <div class="single-content">
                                                <span class="label more-details" onclick="openPopup()">More Details...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="result-rating-bar">
                                        <h3 class="title">Site Ratting:</h3>
                                        <div class="stepper-wrapper">
                                            <div class="stepper-item  <?php 
                                                if($totalRating != '' && $totalRating == 'A'){ echo " active";}
                                                elseif($totalRating != '' && $totalRating == 'B'){ echo "completed";} 
                                                elseif($totalRating != '' && $totalRating == 'C'){ echo "completed error";} 
                                                elseif($totalRating != '' && $totalRating == 'D'){ echo "completed error";} 
                                                elseif($totalRating != '' && $totalRating == 'E'){ echo "completed error";} 
                                            ?>">
                                                <div class="step-counter">
                                                    <?php
                                                        if($totalRating != '' && $totalRating == 'A'){
                                                            ?>
                                                                <span class="number dashicons dashicons-saved"></span>
                                                            <?php
                                                        }
                                                    ?>
                                                </div>
                                                <div class="step-name">Minimal</div>
                                            </div>
                                            <div class="stepper-item <?php 
                                                if($totalRating != '' && $totalRating == 'B'){ echo " active";} 
                                                elseif($totalRating != '' && $totalRating == 'C'){ echo "completed error";} 
                                                elseif($totalRating != '' && $totalRating == 'D'){ echo "completed error";} 
                                                elseif($totalRating != '' && $totalRating == 'E'){ echo "completed error";} 
                                            ?>">
                                                <div class="step-counter">
                                                    <?php
                                                        if($totalRating != '' && $totalRating == 'B'){
                                                            ?>
                                                                <span class="number dashicons dashicons-saved"></span>
                                                            <?php
                                                        }
                                                    ?>
                                                </div>
                                                <div class="step-name">Low</div>
                                            </div>
                                            <div class="stepper-item <?php 
                                                if($totalRating != '' && $totalRating == 'C'){ echo " active";} 
                                                elseif($totalRating != '' && $totalRating == 'D'){ echo "completed error";} 
                                                elseif($totalRating != '' && $totalRating == 'E'){ echo "completed error";} 
                                            ?>">
                                                <div class="step-counter error">
                                                    <?php
                                                        if($totalRating != '' && $totalRating == 'C'){
                                                            ?>
                                                                <span class="number dashicons dashicons-warning"></span>
                                                            <?php
                                                        }
                                                    ?>
                                                </div>
                                                <div class="step-name">Medium <span>Security Risk</span></div>
                                            </div>
                                            <div class="stepper-item <?php  
                                                if($totalRating != '' && $totalRating == 'D'){ echo " active";} 
                                                elseif($totalRating != '' && $totalRating == 'E'){ echo "completed error";} 
                                            ?>">
                                                <div class="step-counter error">
                                                    <?php
                                                        if($totalRating != '' && $totalRating == 'D'){
                                                            ?>
                                                                <span class="number dashicons dashicons-warning"></span>
                                                            <?php
                                                        }
                                                    ?>
                                                </div>
                                                <div class="step-name">High</div>
                                            </div>
                                            <div class="stepper-item <?php 
                                                if($totalRating != '' && $totalRating == 'E'){ echo "active";} 
                                            ?>">
                                                <div class="step-counter error">
                                                    <?php
                                                        if($totalRating != '' && $totalRating == 'E'){
                                                            ?>
                                                                <span class="number dashicons dashicons-warning"></span>
                                                            <?php
                                                        }
                                                    ?>
                                                </div>
                                                <div class="step-name">Critical</div>
                                            </div>
                                        </div>
                                        <div class="result-rating-sm">

                                        </div>
                                    </div>

                                    <div id="site-result-popup" class="site-result-popup">
                                        <div class="popup-content">
                                            <span class="close" onclick="closePopup()">&times;</span>
                                            <!-- Content -->
                                            <div class="popup-content-inner">
                                                <h3 class="popup-content-title">Site Details</h3>
                                                <span class="popup-content-sub-title">System info</span>
                                                <div class="system-info-wrapper">
                                                    <div class="single-item">
                                                        <span class="label">IP addresses:</span>
                                                        <span class="label-value">
                                                            <?php
                                                                foreach ($ipAddresses as $ip) {
                                                                    echo $ip . ', ';
                                                                }
                                                            ?>
                                                        </span>
                                                    </div>
                                                    <?php
                                                        if($cdn != "") {
                                                            ?>
                                                                <div class="single-item">
                                                                    <span class="label">CDN:</span>
                                                                    <span class="label-value"><?php echo $cdn; ?></span>
                                                                </div>
                                                            <?php
                                                        }

                                                        if($cert_issuer != "") {
                                                            ?>
                                                                <div class="single-item">
                                                                    <span class="label">TLS Certificate:</span>
                                                                    <span class="label-value">Issued by <?php echo $cert_issuer; ?></span>
                                                                </div>
                                                            <?php
                                                        }
                                                        if ($api_data !== null && isset($api_data['site']['redirects_to'])){
                                                            ?>
                                                                <div class="single-item redirects">
                                                                    <span class="label">Redirects to:</span>
                                                                    <?php foreach ($api_data['site']['redirects_to'] as $redirect_url): ?>
                                                                        <span class="label-value"><?php echo $redirect_url; ?></span>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            <?php
                                                        }
                                                        if ($api_data !== null && isset($api_data['software']['cms'])) {
                                                            $cms = $api_data['software']['cms'][0];
                                                            foreach ($cms as $key => $value) {
                                                                ?>
                                                                    <div class="single-item">
                                                                        <span class="label"><?php echo $key; ?></span>
                                                                        <span class="label-value"><?php echo $value; ?></span>
                                                                    </div>
                                                                <?php
                                                            }
                                                        }
                                                    ?>
                                                    
                                                </div>
                                                <div class='d-display'>
                                                    <div class='d-details'>
                                                        <details class='tab-control'>
                                                        <summary>Links Found</summary>
                                                        <div class="all-url">
                                                        <?php
                                                            if ($api_data !== null && isset($api_data['links']['urls'])) {
                                                                foreach ($api_data['links']['urls'] as $url) {
                                                                    echo "<span>$url</span>";
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        </details>
                                                        <details class='tab-control'>
                                                        <summary>Javascripts included</summary>
                                                        <div class="all-url">
                                                            <?php 
                                                                if($js_external != ''){
                                                                    foreach ($js_external as $js) {
                                                                        echo "<span>$js</span>";
                                                                    }
                                                                }
                                                                if($js_local != ''){
                                                                    foreach ($js_local as $js_local) {
                                                                        echo "<span>$js_local</span>";
                                                                    }
                                                                }
                                                            ?>
                                                        </div>
                                                        </details>
                                                        <details class='tab-control'>
                                                        <summary>Iframes included Embedded</summary>
                                                        <div class="all-url">
                                                            <span>No plugins found.</span>
                                                        </div>
                                                        </details>
                                                        <details class='tab-control'>
                                                        <summary>Objects included</summary>
                                                        <div class="all-url">
                                                            <span>No plugins found.</span>
                                                        </div>
                                                        </details>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                        if (isset($api_data['warnings']['site_issue']) && is_array($api_data['warnings']['site_issue'])) {
                                            ?>
                                            <div class="result-issue-content">
                                                <?php
                                                foreach ($api_data['warnings']['site_issue'] as $issue) {
                                                    ?>
                                                    <div class="single-issue-item">
                                                        <h3 class="title"><?php echo $issue['type']; ?> Detected</h3>
                                                        <div class="content">
                                                            <span class="url">
                                                                <?php echo $issue['location']; ?>
                                                                <span class="more-details">(More Details)</span>
                                                            </span>
                                                            <div class="more-details-content" style="display:none;">
                                                                <?php
                                                                    foreach ($issue as $key => $value) {
                                                                        if ($value != '') {
                                                                            ?>
                                                                                <code class="info-url"><?php echo htmlspecialchars($key); ?>: <?php echo htmlspecialchars($value); ?></code>
                                                                            <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        
                                            <!-- Add jQuery script for show/hide functionality -->
                                            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                                            <script>
                                                $(document).ready(function () {
                                                    // Hide all .more-details-content initially
                                                    $(".more-details-content").hide();

                                                    $(".more-details").click(function () {
                                                        var moreDetailsContent = $(this).closest(".content").find(".more-details-content");
                                                        moreDetailsContent.toggle();
                                                    
                                                    });
                                                });
                                            </script>
                                            <?php
                                        }
                                    ?>
                                    
                                    <?php
                                        if($anomalyMsg != '' ){
                                            ?>
                                                <div class="result-notice">
                                                    <span>Our automated scan found an issue on some pages of your website. If you believe your website has been hacked, sign up for a complete scan and guaranteed malware removal.</span>
                                                </div>
                                            <?php
                                        }else{
                                            ?>
                                                <div class="result-notice safe">
                                                    <span>Our automated scan did not detect malware on your site. If you still believe that your site has been hacked, sign up for a complete scan, manual audit, and guaranteed malware removal.</span>
                                                </div>
                                            <?php
                                        }
                                    ?>
                                </div>
                                <div class="malware-security-blacklist-status">
                                    <div class="malware-security-wrapper">
                                        <div class="malware-security-top-area">
                                            <h3 class="title">Website Malware & Security</h3>
                                            <ul class="checkmark">
                                                <?php
                                                    $check_risk = '';

                                                    if($totalRating == 'A'){
                                                        $check_risk = 'Minimal Risk';
                                                    }elseif($totalRating == 'B'){
                                                        $check_risk = 'Low Risk';
                                                    }elseif($totalRating == 'C'){
                                                        $check_risk = 'Medium Risk';
                                                    }elseif($totalRating == 'D'){
                                                        $check_risk = 'High Risk';
                                                    }elseif($totalRating == 'E'){
                                                        $check_risk = 'Critical Risk';
                                                    }
                                                ?>
                                                <li>No malware detected by scan <span>(<?php echo $check_risk; ?>)</span></li>
                                                <li>No injected spam detected <span>(<?php echo $check_risk; ?>)</span></li>
                                                <li>No defacements detected <span>(<?php echo $check_risk; ?>)</span></li>      
                                                <li class="li-issue">Site issues detected 
                                                    <span class="li-issue-text">
                                                        (
                                                            <?php 
                                                                if($totalRating == 'A' || $totalRating == 'B'){
                                                                    echo "Low Risk";
                                                                }elseif($totalRating == 'C'){
                                                                    echo "Medium Risk";
                                                                }elseif($totalRating == 'D'){
                                                                    echo "High Risk";
                                                                }elseif($totalRating == 'E'){
                                                                    echo "Critical Risk";
                                                                }
                                                            ?>
                                                        )
                                                    </span>
                                                </li>       
                                            </ul>
                                            <div>
                                                <a  href="#" class="result-heading-button">Request Review</a>
                                            </div>
                                        </div>
                                        <div class="blacklist-status-wrapper">
                                            <h3 class="title">Website Malware & Security</h3>
                                            <ul class="checkmark">
                                                <li>Domain clean by Google Safe Browsing</li>
                                                <li>Domain clean by McAfee</li>
                                                <li>Domain clean by Sucuri Labs</li>
                                                <li>Domain clean by ESET</li>
                                                <li>Domain clean by PhishTank</li>
                                                <li>Domain clean by Yandex</li>
                                                <li>Domain clean by Yandex</li>
                                                <li>Domain clean by Opera</li>
                                            </ul>
                                            <p class="desc">Your site does not appear to be blacklisted. If you still see security warnings on your site, sign up for a more complete scan, manual audit, and guaranteed blacklist removal.</p>
                                        </div>
                                    </div>
                                    <?php
                                        if ($anomalyMsg != '' && isset($api_data['warnings']['site_issue']) && is_array($api_data['warnings']['site_issue'])) {
                                            ?>
                                                <div class="malware-security-bottom-area">
                                                    <?php
                                                        foreach ($api_data['warnings']['site_issue'] as $issue) {
                                                            ?>
                                                                <div class="single-item">
                                                                    <div class="heading-content style-2">
                                                                        <div class="scan-icon error">
                                                                            <span class="dashicons dashicons-warning"></span>   
                                                                        </div>
                                                                        <div class="content">
                                                                            <h3 class="title"><?php echo $issue['type']; ?></h3>
                                                                            <p class="desc"><?php echo $issue['msg']; ?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                        }
                                                    ?>
                                                </div>
                                            <?php
                                        }
                                    ?>
                                </div>
                                <?php
                                    if($certExpires != ''){
                                        ?>
                                            <div class="hardening-improvements">
                                                <h3 class="title">Hardening Improvements</h3>
                                                <div class="result-notice">
                                                    <span class="title">TLS</span>
                                                    <span>Your TLS certificate will expire soon: <?php echo $certExpires; ?>. Please consider obtaining a new certificate for your website.</span>
                                                </div>
                                                <div class="result-notice">
                                                    <span class="title">Security Headers</span>
                                                    <span>Our automated scan found an issue on some pages of your website. If you believe your website has been hacked, sign up for a complete scan and guaranteed malware removal.</span>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    <?php
				} else {
					echo '<p>Invalid or empty API response.</p>';
				}
            }
        } 
    }
}
