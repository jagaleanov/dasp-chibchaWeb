<?php

namespace src\services;

class LayoutService
{
	private static $instance = null;
	private $layout;
	private $script;
	private $style;
	private $js;
	private $css;
	private $title;
	private $description;
	private $messages;

	private function __construct()
	{
		$this->layout = 'default';
		$this->script = null;
		$this->style = null;
		$this->js = null;
		$this->css = null;
		$this->title = 'ChibchaWeb';
		$this->description = 'Hosting Service';
		$this->messages = [];
	}

	// Método estático para obtener la única instancia 
	public static function getInstance()
	{
		// Si la instancia no existe, la crea
		if (self::$instance == null) {
			self::$instance = new LayoutService();
		}
		// Devuelve la única instancia de DatabaseService
		return self::$instance;
	}

	public function view($view = null, $data = [], $return = false)
	{
		$layout			= "layouts/" . $this->layout;
		$view			= $view;
		$loadView		= array('content_for_layout' => $this->render($view, $data, true));

		if ($return)
			return $this->render($layout, $loadView, true);
		else
			$this->render($layout, $loadView, false);
	}

	public function setLayout($layout)
	{
		$this->layout	= $layout;
	}

	public function setScript($script)
	{
		$this->script = $script;
	}

	public function setStyle($style)
	{
		$this->style = $style;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function setMessage($messages = [])
	{
		foreach ($messages as $type => $message) {
			$this->messages[$type] = $message;
		}
	}

	public function setJs($files = [])
	{
		foreach ($files as $file) {
			$this->js .= '<script type="text/javascript" src="' . $file . '"></script>';
		}
	}

	public function setCss($files = [])
	{
		foreach ($files as $file) {
			$this->css .= '<link href="' . $file . '" rel="stylesheet" type="text/css"/>';
		}
	}

	public function getScript()
	{
		return $this->script;
	}

	public function getStyle()
	{
		return $this->style;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getMessages()
	{
		if (empty($this->messages)) {
			return FALSE;
		} else {
			return $this->messages;
		}
	}




	private function render($view, $data = [], $returnContent = false)
	{
		extract($data);
		ob_start();
		include "src/views/$view.php";
		$content = ob_get_clean();

		if ($returnContent) {
			return $content;
		} else {
			print $content;
		}
	}
}
