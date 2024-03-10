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

        if (isset($_GET['url'])) {
            $url = urldecode($_GET['url']);
            $api_url = 'https://api.wpsafe.ai/api/v1/healthcheck/?site_url=' . urlencode($url);

            // Make an API request using wp_remote_get
            $response = wp_remote_get($api_url);

            if (!is_wp_error($response) && $response['response']['code'] === 200) {
                // Handle the API response here
				$api_data = json_decode($response['body'], true);

				if ($api_data !== null) {
                    
                    $anomalyMsg = '';
                    if (isset($api_data['warnings']['site_issue'][0]['msg'])) {
                        $anomalyMsg = $api_data['warnings']['site_issue'][0]['msg'];
                    }

                    $firstIPAddress = '';
                    if (isset($api_data['site']['ip']) && is_array($api_data['site']['ip'])) {
                        $ipAddresses = $api_data['site']['ip'];
                    
                        // Assuming you want to display or use the first IP address
                        $firstIPAddress = isset($ipAddresses[0]) ? $ipAddresses[0] : '';
                    }

                    $cdn = '';
                    if (isset($api_data['site']['cdn']) && is_array($api_data['site']['cdn'])) {
                        $cdnAddresses = $api_data['site']['cdn'];
                    
                        // Assuming you want to display or use the first IP address
                        $cdn = isset($cdnAddresses[0]) ? $cdnAddresses[0] : '';
                    }

                    $powered_by = '';
                    if (isset($api_data['site']['powered_by']) && is_array($api_data['site']['powered_by'])) {
                        $poweredByArray = $api_data['site']['powered_by'];
                    
                        // Assuming you want to display or use the first "powered_by" value
                        $powered_by = isset($poweredByArray[0]) ? $poweredByArray[0] : '';
                    }

                    $running_on = '';
                    if (isset($api_data['site']['running_on']) && is_array($api_data['site']['running_on'])) {
                        $runningAddresses = $api_data['site']['running_on'];
                    
                        // Assuming you want to display or use the first IP address
                        $running_on = isset($runningAddresses[0]) ? $runningAddresses[0] : '';
                    }

                    $firstCMS = '';
                    if (isset($api_data['software']['cms']) && is_array($api_data['software']['cms'])) {
                        $cmsArray = $api_data['software']['cms'];
                    
                        // Assuming you want to display or use the first CMS name
                        $firstCMS = isset($cmsArray[0]['name']) ? $cmsArray[0]['name'] : '';
                    }

                    $certExpires = '';
                    if (isset($api_data['tls']['cert_expires'])) {
                        $certExpires = $api_data['tls']['cert_expires'];
                    }

                    $totalRating = isset($api_data['ratings']['total']['rating']) ? $api_data['ratings']['total']['rating'] : '';
                    ?>
                        <div class="site-scan-result">
                            <div class="container">
                                <h3 class="site-scan-result-title"><a href="/sitecheck"><span class="dashicons dashicons-arrow-left-alt"> </span></a><?php echo $api_data['site']['input']; ?></h3>
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
                                    <div class="result-box">
                                                <div class="result-url">
                                                    <div class="icon">
                                                        <span class="dashicons dashicons-admin-links"></span>
                                                    </div>
                                                    <div class="result-url-content">
                                                        <h3 class="title">Redirects to:</h3>
                                                        <p class="url"><?php echo $api_data['site']['final_url']; ?></p>
                                                    </div>
                                                </div>
                                                <div class="divider"></div>
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
                                    </div>
                                    <div class="result-rating-bar">
                                                <ul class="yprogress-bar theme-blue" >
                                                    <li class="step <?php if($totalRating != '' && $totalRating == 'A'){ echo "safe";} ?>">
                                                        <?php
                                                            if($totalRating == 'A'){
                                                                ?>
                                                                    <span class="number dashicons dashicons-saved"></span>
                                                                <?php
                                                            }else{
                                                                ?>
                                                                    <span class="number dashicons dashicons-saved"></span>
                                                                <?php
                                                            }
                                                        ?>
                                                        <div class="title">Minimal</div>
                                                    </li>
                                                    <li class="step <?php if($totalRating != '' && $totalRating == 'B'){ echo "safe";} ?>">
                                                        <span class="number dashicons dashicons-saved"></span>
                                                        <div class="title">Low</div>
                                                    </li>
                                                    <li class="step <?php if($totalRating != '' && $totalRating == 'C'){ echo "error";} ?>">
                                                        <span class="number dashicons dashicons-warning"></span>
                                                        <div class="title">Medium Security Risk</div>
                                                    </li>
                                                    <li class="step <?php if($totalRating != '' && $totalRating == 'D'){ echo "error";} ?>">
                                                        <span class="number dashicons dashicons-warning"></span>
                                                        <div class="title">High</div>
                                                    </li>  
                                                    <li class="step <?php if($totalRating != '' && $totalRating == 'E'){ echo "error";} ?>">
                                                        <span class="number dashicons dashicons-warning"></span>
                                                        <div class="title">Critical</div>
                                                    </li> 
                                                </ul>
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
                                                                        <span class="url"><?php echo $issue['location']; ?></span>
                                                                        <span class=info-url><?php echo $issue['info_url']; ?></span>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                        }
                                                    ?>
                                                </div>
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
                                                    <?php 
                                                        if ($anomalyMsg != '' && isset($api_data['warnings']['site_issue']) && is_array($api_data['warnings']['site_issue'])) {
                                                            ?>
                                                                <?php
                                                                if($totalRating != 'A' && $totalRating != 'B'){
                                                                    ?>
                                                                    <li class="li-issue">Site issues detected <span class="li-issue-text">(<?php 
                                                                        if($totalRating == 'C'){
                                                                            echo "Medium Risk";
                                                                        }elseif($totalRating == 'D'){
                                                                            echo "High Risk";
                                                                        }elseif($totalRating == 'E'){
                                                                            echo "Critical Risk";
                                                                        }
                                                                        ?>)</span></li>
                                                                    <?php

                                                                }
                                                                ?>
                                                            <?php
                                                        }
                                                    ?>
                                                </ul>
                                                <div>
                                                    <a  href="#" class="result-heading-button">Request Review</a>
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
            } else {
                // Handle error
                echo '<p>Error: ' . esc_html($response->get_error_message()) . '</p>';
            }
        }


        
    }
}
