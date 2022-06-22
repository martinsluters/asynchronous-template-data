<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Wpunit;

use martinsluters\AsynchronousTemplateData\PluginData;
use martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument;

/**
 * Class testing static dependencies.
 */
class PluginDataTest extends \Codeception\TestCase\WPTestCase {

	use \FalseyAssertEqualsDetector\Test;

	/**
	 * PluginData instance - sut.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\PluginData
	 */
	protected PluginData $plugin_data_sut;

	/**
	 * Set up SUT.
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->plugin_data_sut = new PluginData();
	}

	/**
	 * Make sure getKeyPrefix returns only lowercase letters and underscore.
	 *
	 * @return void
	 */
	public function testGetPrefixContainsOnlyUnderscoredLowercaseLetterString(): void {
		$this->assertStringContainsOnlyLowercaseAZUnderscore(
			$this->plugin_data_sut->getKeyPrefix()
		);
	}

	/**
	 * Make sure getKeyPrefix value is the same set in phpcs config file
	 * from the root of the project.
	 *
	 * @return void
	 */
	public function testGetPrefixMatchesPhpcsConfigFile(): void {

		$phpcs_config_hook_prefix = '';
		if ( file_exists( 'phpcs.xml.dist' ) ) {
			$xml = simplexml_load_file( 'phpcs.xml.dist' );
			$phpcs_config_hook_prefix = (string) $xml->xpath( '/ruleset/config[@name="prefixes"]' )[0]->attributes()->value;
		} else {
			exit( 'Failed to open phpcs.xml.dist' );
		}
		$this->assertSame( $phpcs_config_hook_prefix, $this->plugin_data_sut->getKeyPrefix() );
	}

	/**
	 * Make sure main plugin file exists.
	 *
	 * @return void
	 */
	public function testGetMainPluginFilePhysicallyExists() {
		$this->assertFileExists( $this->plugin_data_sut->getMainPluginFile() );
	}

	/**
	 * Make sure getAjaxActionName returns only lowercase letters and underscore.
	 *
	 * @return void
	 */
	public function testGetAjaxActionNameContainsOnlyUnderscoredLowercaseLetterString(): void {
		$this->assertStringContainsOnlyLowercaseAZUnderscore(
			$this->plugin_data_sut->getAjaxActionName()
		);
	}

	/**
	 * Make sure getSecurityNonceActionName returns only lowercase letters and underscore.
	 *
	 * @return void
	 */
	public function testGetSecurityNonceActionNameContainsOnlyUnderscoredLowercaseLetterString(): void {
		$this->assertStringContainsOnlyLowercaseAZUnderscore(
			$this->plugin_data_sut->getSecurityNonceActionName()
		);
	}

	/**
	 * Make sure getSecurityNonceRequestParameterName returns only lowercase letters and underscore.
	 *
	 * @return void
	 */
	public function testGetSecurityNonceRequestParameterNameContainsOnlyUnderscoredLowercaseLetterString(): void {
		$this->assertStringContainsOnlyLowercaseAZUnderscore(
			$this->plugin_data_sut->getSecurityNonceRequestParameterName()
		);
	}

	/**
	 * Make sure getProviderIdentificationPropertyName value matches
	 * property name of martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument.
	 * This way we are sure that getProviderIdentificationPropertyName serves the purpose.
	 *
	 * @return void
	 */
	public function testGetProviderIdentificationPropertyNameIsPropertyOfLookupArgumentInstance(): void {
		$this->assertTrue(
			( new \ReflectionClass( AbstractLookupArgument::class ) )
				->hasProperty(
					$this->plugin_data_sut->getProviderIdentificationPropertyName()
				),
			'Failed asserting that martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument contains property named: ' . $this->plugin_data_sut->getProviderIdentificationPropertyName()
		);
	}

	/**
	 * Make sure a file with name returned from getTemplateFilename
	 * physically exists in directory dedicated for templates.
	 *
	 * @return void
	 */
	public function testGetTemplateFilenamePhysicallyExistsInTemplateDirectory() {
		$this->assertFileExists( $this->plugin_data_sut->getTemplatesDirPath() . $this->plugin_data_sut->getTemplateFilename() );
	}

	/**
	 * Make sure directory dedicated to template files exists
	 * in the project.
	 *
	 * @return void
	 */
	public function testGetTemplatesDirPathDirectoryPhysicallyExists() {
		$this->assertDirectoryExists( $this->plugin_data_sut->getTemplatesDirPath() );
	}

	/**
	 * Make sure that getPluginDirPath really returns root dir path of the plugin
	 *
	 * @return void
	 */
	public function testGetPluginDirPathReturnsRootDirectoryOfPlugin() {
		$this->assertFileExists( $this->plugin_data_sut->getPluginDirPath() . $this->plugin_data_sut->getPluginTextDomain() . '.php' );
		$this->assertFileExists( $this->plugin_data_sut->getPluginDirPath() . 'composer.json' );
	}

	/**
	 * Make sure that getPluginTextDomain matches Composer config file's name attribute
	 *
	 * @return void
	 */
	public function testGetPluginTextDomainIsASubstringComposerJsonConfigNameAttribute() {
		$composer_package_name = '';
		if ( file_exists( 'composer.json' ) ) {
			$composer_data = json_decode(
				file_get_contents( 'composer.json' ), //phpcs:ignore
				true
			);
			$composer_package_name = $composer_data['name'];
		} else {
			exit( 'Failed to open composer.json' );
		}

		$text_domain = $this->plugin_data_sut->getPluginTextDomain();

		$this->assertStringContainsString(
			$text_domain,
			$composer_package_name,
			"Failed asserting that '$text_domain' returned from PluginData::getPluginTextDomain() is a substring of composer.json defined package name '$composer_package_name'."
		);
	}

	/**
	 * Make sure that getMinPHPVersion matches Composer config file's require > php attribute value.
	 *
	 * @return void
	 */
	public function testGetMinPHPVersionShouldMatchComposerJsonConfigPHPRequirement() {
		$composer_required_php_version = '';
		if ( file_exists( 'composer.json' ) ) {
			$composer_data = json_decode(
				file_get_contents( 'composer.json' ),
				true
			);
			preg_match( '/[0-9.]+/', $composer_data['require']['php'], $matches );
			$composer_required_php_version = $matches[0];
		} else {
			exit( 'Failed to open composer.json' );
		}

		$this->assertSame( $composer_required_php_version, $this->plugin_data_sut->getMinPHPVersion() );
	}

	/**
	 * Make sure that getPluginVersion matches Composer config file's version attribute value.
	 *
	 * @return void
	 */
	public function testgetPluginVersionComposerJsonConfigPHPRequirement() {
		$composer_package_version = '';
		if ( file_exists( 'composer.json' ) ) {
			$composer_data = json_decode(
				file_get_contents( 'composer.json' ),
				true
			);
			$composer_package_version = $composer_data['version'];
		} else {
			exit( 'Failed to open composer.json' );
		}

		$this->assertSame( $composer_package_version, $this->plugin_data_sut->getPluginVersion() );
	}

	/**
	 * Asserts that a string contains only lowercase letters and underscores.
	 *
	 * @param string $string String in question.
	 * @return void
	 */
	private function assertStringContainsOnlyLowercaseAZUnderscore( string $string ): void {
		$this->assertRegExp(
			'/^[a-z_]+$/',
			$string
		);
	}
}
