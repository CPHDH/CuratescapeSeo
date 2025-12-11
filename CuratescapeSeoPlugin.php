<?php
include 'functions.php';
class CuratescapeSeoPlugin extends Omeka_Plugin_AbstractPlugin{
	protected $_hooks = array(
		'admin_head',
		'config_form',
		'config',
		'initialize',
		'install',
		'public_head',
		'uninstall',
	);
	protected $_options = array(
		'curatescapeseo_meta_image' => null,
	);
	public function hookAdminHead($args)
	{
		if(
			is_current_url('/admin/plugins/config?name=CuratescapeSeo') ||
			is_current_url('/admin/plugins/config/name/CuratescapeSeo')
		){
			queue_css_file('seo-config', 'all', false, 'css', get_plugin_ini('CuratescapeSEO', 'version'));
		}
	}
	public function hookInitialize()
	{
		add_translation_source(dirname(__FILE__).'/languages');
	}
	public function hookInstall()
	{
		return $this->_installOptions();
	}
	public function hookUninstall()
	{
		return $this->_uninstallOptions();
	}
	public function hookConfig()
	{
		set_option('curatescapeseo_meta_image', $_POST['curatescapeseo_meta_image']);
	}
	public function hookConfigForm()
	{
		?>
		<p class="intro"><?php echo __('To learn about Curatescape mobile apps for iOS and Android, visit %1s. For support, create an account at %2s.', '<a href="https://curatescape.org" target="_blank">curatescape.org</a>', '<a href="https://forum.curatescape.org" target="_blank">forum.curatescape.org</a>');?></p>
		<fieldset>
			<legend><?php echo __('Preview Settings'); ?></legend>
			<p><?php echo __('Use the following options to control the way your content is represented on search engines and social media websites. Refer to the <a target="_blank" href="https://omeka.org/classic/plugins/CuratescapeSeo/">plugin documentation</a> for additional details and frequently asked questions.');?></p>
			<!-- Meta Image -->
			<?php echo configFormText('curatescapeseo_meta_image', 'Meta Image', __('Enter the URL for a PNG or JPG file to serve as the fallback image to represent your site on social media and search engine results. Used only when there is not a content-related image available (for example, on the homepage and browse pages). Recommended dimensions: 1200px × 630px (1.91:1). Developers may refer to <a target="_blank" href="https://omeka.org/classic/plugins/CuratescapeSeo/">plugin documentation</a> for alternate theme-based method.'), 'Example: '.WEB_ROOT.'/meta.png');?>
		</fieldset>
		<?
	}
	public function hookPublicHead($args)
	{
		return metaTags($args);
	}
}
