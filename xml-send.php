<?php
    /*
     * Define POST URL and also payload
     */
    define('XML_PAYLOAD', '<?xml version="1.0" encoding="UTF-8"?>
<AutoApplication apptype="N" appmethod="W" affid="2543" ifmgpgm="IMP" dealerid="4235" reference_number="" ipaddress="">
<loan>
<loan_amount>22000</loan_amount>
<loan_term desc="D"/>
<down_payment>2000</down_payment>
<has_trade_in desc="Y"/>
<trade_in_amount>234</trade_in_amount>
<auto>
<make>VOLK</make>
<model>Jetta</model>
<modelyear>1998</modelyear>
<mileage>40000</mileage>
<vin>VKXCV3428593785930GHGD003848</vin>
</auto>
</loan>
<primaryborrower>
<fname>Robert</fname>
<mi>N</mi>
<lname>Marley</lname>
<ssn>155556789</ssn>
<dob>05/21/1947</dob>
<email_address>bob@jammin.com</email_address>
<marital_status desc="M"/>
<mobile_phone>5085551212</mobile_phone>
<employment_status desc="E"/>
<mothers_maiden>Nature</mothers_maiden>
<credit>
<bankrupt desc="N"/>
<other_monthly_income desc="alimony">1500</other_monthly_income>
<credit_score desc="G">800</credit_score>
</credit>
<current_residence>
<address>
<street_number>1776</street_number>
<street_name>Patriots</street_name>
<street_type>WY</street_type>
<unit_number desc="A">12</unit_number>
<city>Foxboro</city>
<state>MA</state>
<postal_code>02035</postal_code>
</address>
<home_phone>5085431776</home_phone>
<monthly_housing_payment desc="R">1000</monthly_housing_payment>
<years_resided>3</years_resided>
<months_resided>6</months_resided>
</current_residence>
<previous_residence>
<address>
<street_number>33</street_number>
<street_name>Causeway</street_name>
<street_type>ST</street_type>
<unit_number desc="A">32</unit_number>
<city>Boston</city>
<state>MA</state>
<postal_code>02134</postal_code>
</address>
<years_resided>5</years_resided>
<months_resided>10</months_resided>
</previous_residence>
<current_employer>
<company_name>New England Patriots</company_name>
<address>
<street_number>19</street_number>
<street_name>Washington</street_name>
<street_type>ST</street_type>
<unit_number desc="U">14</unit_number>
<city>Foxboro</city>
<state>MA</state>
<postal_code>02035</postal_code>
</address>
<work_phone>5085431776</work_phone>
<work_ext>22</work_ext>
<position>Strength Coach</position>
<monthly_wages>8000</monthly_wages>
<years_of_service>4</years_of_service>
<months_of_service>5</months_of_service>
</current_employer>
<previous_employer>
<company_name>Western New England College</company_name>
<address>
<street_number>18</street_number>
<street_name>Wilbraham</street_name>
<street_type>RD</street_type>
<unit_number desc="U">88</unit_number>
<city>Springfield</city>
<state>MA</state>
<postal_code>02119</postal_code>
</address>
<years_of_service>2</years_of_service>
<months_of_service>3</months_of_service>
</previous_employer>
</primaryborrower>
<coborrower>
<fname>Rita</fname>
<mi>S</mi>
<lname>Marley</lname>
<ssn>123466680</ssn>
<dob>06/14/1954</dob>
<email_address>rita@jammin.com</email_address>
<marital_status desc="M"/>
<mobile_phone>5085551212</mobile_phone>
<credit>
<bankrupt desc="N"/>
<other_monthly_income desc="alimony">1500</other_monthly_income>
<credit_score desc="G">800</credit_score>
</credit>
<current_residence>
<address>
<street_number>1776</street_number>
<street_name>Patriots</street_name>
<street_type>WY</street_type>
<unit_number desc="A">12</unit_number>
<city>Foxboro</city>
<state>MA</state>
<postal_code>02035</postal_code>
</address>
<home_phone>5085431776</home_phone>
<monthly_housing_payment desc="R">1000</monthly_housing_payment>
<years_resided>3</years_resided>
<months_resided>6</months_resided>
</current_residence>
<previous_residence>
<address>
<street_number>33</street_number>
<street_name>Causeway</street_name>
<street_type>ST</street_type>
<unit_number desc="A">32</unit_number>
<city>Boston</city>
<state>MA</state>
<postal_code>02134</postal_code>
</address>
<years_resided>5</years_resided>
<months_resided>10</months_resided>
</previous_residence>
<current_employer>
<company_name>I-Three Ltd.</company_name>
<address>
<street_number>16</street_number>
<street_name>Trenchtown</street_name>
<street_type>RD</street_type>
<unit_number desc="A">13</unit_number>
<city>Kingston</city>
<state>MA</state>
<postal_code>02035</postal_code>
</address>
<work_phone>5085431776</work_phone>
<work_ext>22</work_ext>
<position>Singer</position>
<monthly_wages>8000</monthly_wages>
<years_of_service>4</years_of_service>
<months_of_service>5</months_of_service>
</current_employer>
<previous_employer>
<company_name>Wailers Company</company_name>
<address>
<street_number>19</street_number>
<street_name>Rasta</street_name>
<street_type>WY</street_type>
<unit_number desc="U">88</unit_number>
<city>Springfield</city>
<state>MA</state>
<postal_code>02119</postal_code>
</address>
<years_of_service>2</years_of_service>
<months_of_service>3</months_of_service>
</previous_employer>
</coborrower>
</AutoApplication>');
    define('XML_POST_URL', 'https://app.interactivefmg.com/partners/standard/post.aspx');
       
    /*
     * Initialize handle and set options
     */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, XML_POST_URL);
    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);
    curl_setopt($ch, CURLOPT_POSTFIELDS, XML_PAYLOAD);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: close'));
   
    /*
     * Execute the request and also time the transaction
     */
    $start = array_sum(explode(' ', microtime()));
    $result = curl_exec($ch);
    $stop = array_sum(explode(' ', microtime()));
    $totalTime = $stop - $start;
   
    /*
     * Check for errors
     */
    if ( curl_errno($ch) ) {
        $result = 'cURL ERROR -> ' . curl_errno($ch) . ': ' . curl_error($ch);
    } else {
        $returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        switch($returnCode){
            case 200:
                break;
            default:
                $result = 'HTTP ERROR -> ' . $returnCode;
                break;
        }
    }
   
    /*
     * Close the handle
     */
    curl_close($ch);
   
    /*
     * Output the results and time
     */
    echo 'Total time for request: ' . $totalTime . "\n";
    echo $result;  
   
    /*
     * Exit the script
     */
    exit(0);
?>