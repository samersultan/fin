<?php
// Check if page already exists
$existingPageTitles = array();
$existing_pages = get_pages();
if($existing_pages != ''){
	foreach ($existing_pages as $page) {
		$existingPageTitles[] = $page->post_title;
	}
}
if(!in_array('Privacy', $existingPageTitles) && !in_array('Privacy Policy', $existingPageTitles) ) {
	$add_page = array(
		'post_type' => 'page',
		'post_status' => 'publish',
		'post_title' => 'Privacy Policy',
		'post_content' => '<dl id="privacy">
		<dt>What information do we collect?</dt>
		<dd><p>We collect information from you when you fill out a form.</p>
		<p>When ordering or registering on our site, as appropriate, you may be asked to enter your: name, e-mail address or phone number. You may, however, visit our site anonymously.</p></dd>
		<dt>What do we use your information for?</dt>
		<dd><p>Any of the information we collect from you may be used in one of the following ways: 
		<ul>
		<li>To improve customer service
		(your information helps us to more effectively respond to your customer service requests and support needs)</li>
		<li>To send periodic emails
		The email address you provide for order processing, may be used to send you information and updates pertaining to your order, in addition to receiving occasional company news, updates, related product or service information, etc.</li>
		</ul></p>
		<small>
		Note: If at any time you would like to unsubscribe from receiving future emails, we include detailed unsubscribe instructions at the bottom of each email.</small></dd>
		<dt>How do we protect your information?</dt>
		<dd>We implement a variety of security measures to maintain the safety of your personal information when you enter, submit, or access your personal information.</dd>
		<dt>Do we use cookies?</dt>
		<dd>We do not use cookies.</dd>
		<dt>Do we disclose any information to outside parties?</dt> 
		<dd>We do not sell, trade, or otherwise transfer to outside parties your personally identifiable information. This does not include trusted third parties who assist us in operating our website, conducting our business, or servicing you, so long as those parties agree to keep this information confidential. We may also release your information when we believe release is appropriate to comply with the law, enforce our site policies, or protect ours or others rights, property, or safety. However, non-personally identifiable visitor information may be provided to other parties for marketing, advertising, or other uses.</dd>
		<dt>Third party links</dt>
		<dd>Occasionally, at our discretion, we may include or offer third party products or services on our website. These third party sites have separate and independent privacy policies. We therefore have no responsibility or liability for the content and activities of these linked sites. Nonetheless, we seek to protect the integrity of our site and welcome any feedback about these sites.</dd>
		<dt>California Online Privacy Protection Act Compliance</dt>
		<dd>Because we value your privacy we have taken the necessary precautions to be in compliance with the California Online Privacy Protection Act. We therefore will not distribute your personal information to outside parties without your consent.</dd>
		<dt>Your Consent</dt>
		<dd>By using our site, you consent to our online privacy policy.</dd>
		<dt>Changes to our Privacy Policy</dt>
		<dd>If we decide to change our privacy policy, we will post those changes on this page.</dd>
		</dl>
		' 
	);
	$page = wp_insert_post($add_page);

	// Add to 'secondary' menu
	fin_add_to_menu($page,'secondary_menu');
}
?>