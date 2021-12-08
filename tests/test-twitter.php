<?php

/**
 * @noinspection PhpIllegalPsrClassPathInspection
 * @noinspection PhpMultipleClassDeclarationsInspection
 */

class Test_Twitter extends WP_UnitTestCase {
	/**
	 * @link https://developer.twitter.com/en/docs/authentication/oauth-1-0a/creating-a-signature
	 */
	public function test_signature() {
		$url    = 'https://api.twitter.com/1.1/statuses/update.json';
		$method = 'POST';

		$params = [
			'include_entities'       => 'true',
			'status'                 => 'Hello Ladies + Gentlemen, a signed OAuth request!',
			'oauth_consumer_key'     => 'xvz1evFS4wEEPTGEFPHBog',
			'oauth_nonce'            => 'kYjzVBB8Y0ZFabxSWbWovY3uYSQ2pTgmZeNu2VS4cg',
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_timestamp'        => '1318622958',
			'oauth_token'            => '370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb',
			'oauth_version'          => '1.0'
		];

		$consumer_secret    = 'kAcSOqF21Fu85e7zjz7ZN2U4ZRhfV3WpwPAoE3Z7kBw';
		$oauth_token_secret = 'LswwdoUaIvS8ltyTt5jkRh4J50vUPVVHtR2YPi5kE';

		ksort( $params );
		$string = build_query( rawurlencode_deep( $params ) );

		$this->assertEquals(
			'include_entities=true&oauth_consumer_key=xvz1evFS4wEEPTGEFPHBog&oauth_nonce=kYjzVBB8Y0ZFabxSWbWovY3uYSQ2pTgmZeNu2VS4cg&oauth_signature_method=HMAC-SHA1&oauth_timestamp=1318622958&oauth_token=370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb&oauth_version=1.0&status=Hello%20Ladies%20%2B%20Gentlemen%2C%20a%20signed%20OAuth%20request%21',
			$string
		);

		$base_sring = $method . '&' . rawurlencode( $url ) . '&' . rawurlencode( $string );

		$this->assertEquals(
			'POST&https%3A%2F%2Fapi.twitter.com%2F1.1%2Fstatuses%2Fupdate.json&include_entities%3Dtrue%26oauth_consumer_key%3Dxvz1evFS4wEEPTGEFPHBog%26oauth_nonce%3DkYjzVBB8Y0ZFabxSWbWovY3uYSQ2pTgmZeNu2VS4cg%26oauth_signature_method%3DHMAC-SHA1%26oauth_timestamp%3D1318622958%26oauth_token%3D370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb%26oauth_version%3D1.0%26status%3DHello%2520Ladies%2520%252B%2520Gentlemen%252C%2520a%2520signed%2520OAuth%2520request%2521',
			$base_sring
		);

		$sign_key = rawurlencode( $consumer_secret ) . '&' . rawurlencode( $oauth_token_secret );

		$this->assertEquals(
			'kAcSOqF21Fu85e7zjz7ZN2U4ZRhfV3WpwPAoE3Z7kBw&LswwdoUaIvS8ltyTt5jkRh4J50vUPVVHtR2YPi5kE',
			$sign_key
		);

		$signature = hash_hmac( 'sha1', $base_sring, $sign_key, true );

		$signature_base64 = base64_encode( $signature );

		$this->assertEquals( 'hCtSmYh+iHYCEqBWrE7C7hYmtUk=', $signature_base64 );
	}

	public function test_build_oauth_signature() {
		$twitter = new NSL_Auth_Twitter();

		$twitter->set_credential(
			'xvz1evFS4wEEPTGEFPHBog',
			'kAcSOqF21Fu85e7zjz7ZN2U4ZRhfV3WpwPAoE3Z7kBw'
		);

		$twitter->set_oauth_token(
			'370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb',
			'LswwdoUaIvS8ltyTt5jkRh4J50vUPVVHtR2YPi5kE'
		);

		$twitter->set_oauth_nonce( 'kYjzVBB8Y0ZFabxSWbWovY3uYSQ2pTgmZeNu2VS4cg' );

		$twitter->set_oauth_timestamp( '1318622958' );

		$signature = $twitter->build_oauth_signature(
			'https://api.twitter.com/1.1/statuses/update.json?include_entities=true',
			'post',
			[
				'status' => 'Hello Ladies + Gentlemen, a signed OAuth request!',
			]
		);

		$this->assertEquals( 'hCtSmYh+iHYCEqBWrE7C7hYmtUk=', $signature );
	}
}