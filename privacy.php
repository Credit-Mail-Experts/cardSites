<?php
session_start();
ob_start();
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Privacy Notice</title>

        <?php
        require "req/head.php";
        ?>
    </head>

    <body>
        <?php require "req/header.php"; ?>

        <div id="content">
            <section id="privacy">
                <h1>PRIVACY NOTICE EFFECTIVE 05/01/2015</h1>
                <p>
                    This Privacy Notice explains our data collection and privacy practices for users of all the pages of our website,
                    <?php echo $site->html->privacySiteAnchor ?>
                    . If you do not agree to these practices, please do not continue to use this site. Your continued use of this site constitutes your agreement to the following terms. This Privacy Notice applies to those pages of our site that are available to the general public and/or entering a code or other number to access.
                </p>
                <p>
                    By submitting your information you agree to this statement:<br>
                    &quot;<span style='font-weight: bolder'>I expressly consent and agree to Credit Mail Experts (and its affiliates, agents, assigns and service providers) contacting me by the following methods, including but not limited to, any telephone dialing system, sending text messages or eMails using any eMail address I provide now or in the future, using manual calling methods, pre-recorded/artificial voice messages and/or use of an automatic dialing device or system, as applicable</span>&quot;
                </p>

                <p>
                    In connection with your transaction,
                    <?php echo $site->html->privacySiteAnchor ?>
                    may obtain information about you, that you have provided or has been obtained by various sources.
                </p>

                <p>We may collect the following nonpublic personal information about you:</p>

                <ul>
                    <li>
                        information we receive from you on applications or other forms, such as your name, address, email address, home telephone number, work telephone number , mobile/cellular number social security number, income, and obligations;
                    </li>
                    <li>
                        information about your transaction with us or others, such as the products that you have purchased, financed, or leased with or through
                        <?php echo $site->html->privacySiteAnchor ?>
                        ; and
                    </li>
                    <li>
                        information we receive from a consumer-reporting agency, such as your creditworthiness and credit history.
                    </li>
                </ul>

                <div>
                    <p class='privacyList'>
                        We may disclose some or all of the information we collect and /or you supply, to companies that perform marketing services on our behalf or to other financial institutions with whom we have joint marketing agreements. We may make such disclosures about you as a consumer, customer, or former customer.
                    </p>
                    <p class='privacyList'>
                        We may also disclose nonpublic personal information about you as a consumer, customer, or former customer to non-affiliated third parties as permitted or required by law, such as information provided to certain governmental agencies, or in response to a subpoena or court order.
                    </p>
                    <p class='privacyList'>
                        We restrict access to nonpublic personal information about you to those employees who need to know that information to provide products or services to you. We maintain physical, electronic, and/or procedural safeguards that comply with federal regulations to guard your nonpublic personal information.
                    </p>
                    <p class='privacyList'>
                        We may disclose some of the information we collect to companies that perform marketing services on our behalf or to other financial institutions with which we have joint marketing agreements. We may make such disclosures about you as a consumer, customer, or former customer.
                    </p>
                    <p class='privacyList'>
                        We may also disclose nonpublic personal information about you as a consumer, customer, or former customer to non-affiliated third parties as permitted or required by law, such as information provided to certain governmental agencies, or in response to a subpoena or court order.
                    </p>
                    <p class='privacyList'>
                        We restrict access to nonpublic personal information about you to those employees who need to know that information to provide products or services to you. We maintain physical, electronic, and/or procedural safeguards that comply with federal regulations to guard your nonpublic personal information.
                    </p>
                    <p>
                        We collect anonymous traffic data on our site through the use of cookies, a small file containing a string of characters that uniquely identifies your browser. When you visit our site, we set one or more cookies in your browser. We use cookies to improve the quality of our service, including for storing user preferences, tracking site visits and providing information to you while on our site. The Help feature on most Internet browsers will tell you how to prevent your browser from accepting new cookies, how to have the browser notify you when you receive a new cookie, or how to disable cookies altogether. If you set your browser to decline cookies, you may not be able to get the benefit of the features of this website or other websites that you visit.
                    </p>
                    <p>
                        Our servers may automatically record information that your Internet browser sends whenever you visit our site. These server logs may include information such as your Web request; the Internet Protocol (IP) address you use to connect your computer to the Internet; the full Uniform Resource Locator (URL) clickstream to, through, and from our Web site, including date and time of your visit; browser type; and browser language.
                    </p>
                    <p>
                        When you access our website on your mobile device, we may receive information about your location and your mobile device. We may use this information to provide you with location-based services, such as advertising, search results, and other personalized content. Most mobile devices allow you to turn off location services. Most likely, these controls are located in the device's settings menu. See your mobile device guide for additional details.
                    </p>
                    <p>
                        Our site may present links to other websites in a format that enables us to keep track of whether and how often these links have been followed. Once you leave our site by linking to another site, we assume no responsibility for the information collection or privacy practices of that site and you should read their privacy notice before providing any information.
                    </p>
                    <p>
                        We reserve the right to use and disclose any non-public personal information about our website users or former users in any manner not prohibited by applicable law.
                    </p>
                    <p>
                        We may periodically change the terms of this Privacy Notice. When we do so, we will post the revised date at the top of the page in the same manner as the date appears here. You should check here for changes in our practices when you visit our site.
                    </p>
                    <p>
                        If you have questions about our data collection, sharing, and privacy practices, please send us an email at
                        <?php echo $site->html->privacyEmailAnchor ?>
                        .
                    </p>
            </section>
            <br />
        </div>

        <?php require "req/footer.php"; ?>

    </body>
</html>
