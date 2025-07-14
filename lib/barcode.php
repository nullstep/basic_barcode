<?php

class ColourException extends \Exception {}

// CSS

abstract class Css {
	protected function getColorObjFromJs($color) {
		if (!isset($color[2]) || (strpos('tgrc', $color[2]) === false)) {
			 throw new ColorException('invalid javascript color: '.$color);
		}
		switch ($color[2]) {
			case 'g':
				$rex = '/[\[][\"\']g[\"\'][\,]([0-9\.]+)[\]]/';
				if (preg_match($rex, $color, $col) !== 1) {
					throw new ColorException('invalid javascript color: '.$color);
				}
				return new Gray(array('gray' => $col[1], 'alpha' => 1));
			case 'r':
				$rex = '/[\[][\"\']rgb[\"\'][\,]([0-9\.]+)[\,]([0-9\.]+)[\,]([0-9\.]+)[\]]/';
				if (preg_match($rex, $color, $col) !== 1) {
					throw new ColorException('invalid javascript color: '.$color);
				}
				return new Rgb([
					'red'   => $col[1],
					'green' => $col[2],
					'blue'  => $col[3],
					'alpha' => 1
				]);
			case 'c':
				$rex = '/[\[][\"\']cmyk[\"\'][\,]([0-9\.]+)[\,]([0-9\.]+)[\,]([0-9\.]+)[\,]([0-9\.]+)[\]]/';
				if (preg_match($rex, $color, $col) !== 1) {
					throw new ColorException('invalid javascript color: '.$color);
				}
				return new Cmyk([
					'cyan'    => $col[1],
					'magenta' => $col[2],
					'yellow'  => $col[3],
					'key'     => $col[4],
					'alpha'   => 1
				]);
		}
		// case 't'
		return null;
	}

	protected function getColorObjFromCss($type, $color) {
		switch ($type) {
			case 'g':
				return $this->getColorObjFromCssGray($color);
			case 'rgb':
			case 'rgba':
				return $this->getColorObjFromCssRgb($color);
			case 'hsl':
			case 'hsla':
				return $this->getColorObjFromCssHsl($color);
			case 'cmyk':
			case 'cmyka':
				return $this->getColorObjFromCssCmyk($color);
		}
		// case 't'
		return null;
	}

	private function getColorObjFromCssGray($color) {
		$rex = '/[\(]([0-9\%]+)[\)]/';
		if (preg_match($rex, $color, $col) !== 1) {
			throw new ColorException('invalid css color: '.$color);
		}
		return new Gray([
			'gray' => $this->normalizeValue($col[1], 255),
			'alpha' => 1
		]);
	}

	private function getColorObjFromCssRgb($color) {
		$rex = '/[\(]([0-9\%]+)[\,]([0-9\%]+)[\,]([0-9\%]+)[\,]?([0-9\.]*)[\)]/';
		if (preg_match($rex, $color, $col) !== 1) {
			throw new ColorException('invalid css color: '.$color);
		}
		return new Rgb([
			'red' => $this->normalizeValue($col[1], 255),
			'green' => $this->normalizeValue($col[2], 255),
			'blue' => $this->normalizeValue($col[3], 255),
			'alpha' => (isset($col[4][0]) ? $col[4] : 1)
		]);
	}

	private function getColorObjFromCssHsl($color) {
		$rex = '/[\(]([0-9\%]+)[\,]([0-9\%]+)[\,]([0-9\%]+)[\,]?([0-9\.]*)[\)]/';
		if (preg_match($rex, $color, $col) !== 1) {
			throw new ColorException('invalid css color: '.$color);
		}
		return new Hsl([
			'hue' => $this->normalizeValue($col[1], 360),
			'saturation' => $this->normalizeValue($col[2], 1),
			'lightness' => $this->normalizeValue($col[3], 1),
			'alpha' => (isset($col[4][0]) ? $col[4] : 1)
		]);
	}

	private function getColorObjFromCssCmyk($color) {
		$rex = '/[\(]([0-9\%]+)[\,]([0-9\%]+)[\,]([0-9\%]+)[\,]([0-9\%]+)[\,]?([0-9\.]*)[\)]/';
		if (preg_match($rex, $color, $col) !== 1) {
			throw new ColorException('invalid css color: '.$color);
		}
		return new Cmyk([
			'cyan' => $this->normalizeValue($col[1], 100),
			'magenta' => $this->normalizeValue($col[2], 100),
			'yellow' => $this->normalizeValue($col[3], 100),
			'key' => $this->normalizeValue($col[4], 100),
			'alpha' => (isset($col[5][0]) ? $col[5] : 1)
		]);
	}
}

// WEB

class Web extends Css {
	protected static $webhex = [
		'aliceblue' => 'f0f8ffff',
		'antiquewhite' => 'faebd7ff',
		'aqua' => '00ffffff',
		'aquamarine' => '7fffd4ff',
		'azure' => 'f0ffffff',
		'beige' => 'f5f5dcff',
		'bisque' => 'ffe4c4ff',
		'black' => '000000ff',
		'blanchedalmond' => 'ffebcdff',
		'blue' => '0000ffff',
		'blueviolet' => '8a2be2ff',
		'brown' => 'a52a2aff',
		'burlywood' => 'deb887ff',
		'cadetblue' => '5f9ea0ff',
		'chartreuse' => '7fff00ff',
		'chocolate' => 'd2691eff',
		'coral' => 'ff7f50ff',
		'cornflowerblue' => '6495edff',
		'cornsilk' => 'fff8dcff',
		'crimson' => 'dc143cff',
		'cyan' => '00ffffff',
		'darkblue' => '00008bff',
		'darkcyan' => '008b8bff',
		'darkgoldenrod' => 'b8860bff',
		'dkgray' => 'a9a9a9ff',
		'darkgray' => 'a9a9a9ff',
		'darkgrey' => 'a9a9a9ff',
		'darkgreen' => '006400ff',
		'darkkhaki' => 'bdb76bff',
		'darkmagenta' => '8b008bff',
		'darkolivegreen' => '556b2fff',
		'darkorange' => 'ff8c00ff',
		'darkorchid' => '9932ccff',
		'darkred' => '8b0000ff',
		'darksalmon' => 'e9967aff',
		'darkseagreen' => '8fbc8fff',
		'darkslateblue' => '483d8bff',
		'darkslategray' => '2f4f4fff',
		'darkslategrey' => '2f4f4fff',
		'darkturquoise' => '00ced1ff',
		'darkviolet' => '9400d3ff',
		'deeppink' => 'ff1493ff',
		'deepskyblue' => '00bfffff',
		'dimgray' => '696969ff',
		'dimgrey' => '696969ff',
		'dodgerblue' => '1e90ffff',
		'firebrick' => 'b22222ff',
		'floralwhite' => 'fffaf0ff',
		'forestgreen' => '228b22ff',
		'fuchsia' => 'ff00ffff',
		'gainsboro' => 'dcdcdcff',
		'ghostwhite' => 'f8f8ffff',
		'gold' => 'ffd700ff',
		'goldenrod' => 'daa520ff',
		'gray' => '808080ff',
		'grey' => '808080ff',
		'green' => '008000ff',
		'greenyellow' => 'adff2fff',
		'honeydew' => 'f0fff0ff',
		'hotpink' => 'ff69b4ff',
		'indianred' => 'cd5c5cff',
		'indigo' => '4b0082ff',
		'ivory' => 'fffff0ff',
		'khaki' => 'f0e68cff',
		'lavender' => 'e6e6faff',
		'lavenderblush' => 'fff0f5ff',
		'lawngreen' => '7cfc00ff',
		'lemonchiffon' => 'fffacdff',
		'lightblue' => 'add8e6ff',
		'lightcoral' => 'f08080ff',
		'lightcyan' => 'e0ffffff',
		'lightgoldenrodyellow' => 'fafad2ff',
		'ltgray' => 'd3d3d3ff',
		'lightgray' => 'd3d3d3ff',
		'lightgrey' => 'd3d3d3ff',
		'lightgreen' => '90ee90ff',
		'lightpink' => 'ffb6c1ff',
		'lightsalmon' => 'ffa07aff',
		'lightseagreen' => '20b2aaff',
		'lightskyblue' => '87cefaff',
		'lightslategray' => '778899ff',
		'lightslategrey' => '778899ff',
		'lightsteelblue' => 'b0c4deff',
		'lightyellow' => 'ffffe0ff',
		'lime' => '00ff00ff',
		'limegreen' => '32cd32ff',
		'linen' => 'faf0e6ff',
		'magenta' => 'ff00ffff',
		'maroon' => '800000ff',
		'mediumaquamarine' => '66cdaaff',
		'mediumblue' => '0000cdff',
		'mediumorchid' => 'ba55d3ff',
		'mediumpurple' => '9370d8ff',
		'mediumseagreen' => '3cb371ff',
		'mediumslateblue' => '7b68eeff',
		'mediumspringgreen' => '00fa9aff',
		'mediumturquoise' => '48d1ccff',
		'mediumvioletred' => 'c71585ff',
		'midnightblue' => '191970ff',
		'mintcream' => 'f5fffaff',
		'mistyrose' => 'ffe4e1ff',
		'moccasin' => 'ffe4b5ff',
		'navajowhite' => 'ffdeadff',
		'navy' => '000080ff',
		'oldlace' => 'fdf5e6ff',
		'olive' => '808000ff',
		'olivedrab' => '6b8e23ff',
		'orange' => 'ffa500ff',
		'orangered' => 'ff4500ff',
		'orchid' => 'da70d6ff',
		'palegoldenrod' => 'eee8aaff',
		'palegreen' => '98fb98ff',
		'paleturquoise' => 'afeeeeff',
		'palevioletred' => 'd87093ff',
		'papayawhip' => 'ffefd5ff',
		'peachpuff' => 'ffdab9ff',
		'peru' => 'cd853fff',
		'pink' => 'ffc0cbff',
		'plum' => 'dda0ddff',
		'powderblue' => 'b0e0e6ff',
		'purple' => '800080ff',
		'red' => 'ff0000ff',
		'rosybrown' => 'bc8f8fff',
		'royalblue' => '4169e1ff',
		'saddlebrown' => '8b4513ff',
		'salmon' => 'fa8072ff',
		'sandybrown' => 'f4a460ff',
		'seagreen' => '2e8b57ff',
		'seashell' => 'fff5eeff',
		'sienna' => 'a0522dff',
		'silver' => 'c0c0c0ff',
		'skyblue' => '87ceebff',
		'slateblue' => '6a5acdff',
		'slategray' => '708090ff',
		'slategrey' => '708090ff',
		'snow' => 'fffafaff',
		'springgreen' => '00ff7fff',
		'steelblue' => '4682b4ff',
		'tan' => 'd2b48cff',
		'teal' => '008080ff',
		'thistle' => 'd8bfd8ff',
		'tomato' => 'ff6347ff',
		'turquoise' => '40e0d0ff',
		'violet' => 'ee82eeff',
		'wheat' => 'f5deb3ff',
		'white' => 'ffffffff',
		'whitesmoke' => 'f5f5f5ff',
		'yellow' => 'ffff00ff',
		'yellowgreen' => '9acd32ff'
	];

	public function getMap() {
		return self::$webhex;
	}

	public function getHexFromName($name) {
		$name = strtolower($name);
		if (($dotpos = strpos($name, '.')) !== false) {
			$name = substr($name, ($dotpos + 1));
		}
		if (empty(self::$webhex[$name])) {
			throw new ColorException('unable to find the color hex for the name: '.$name);
		}
		return self::$webhex[$name];
	}

	public function getNameFromHex($hex) {
		$name = array_search($this->extractHexCode($hex), self::$webhex, true);
		if ($name === false) {
			throw new ColorException('unable to find the color name for the hex code: '.$hex);
		}
		return $name;
	}

	public function extractHexCode($hex) {
		if (preg_match('/^[#]?([0-9a-f]{3,8})$/', strtolower($hex), $match) !== 1) {
			throw new ColorException('unable to extract the color hash: '.$hex);
		}
		$hex = $match[1];
		switch (strlen($hex)) {
			case 3:
				return $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2].'ff';
			case 4:
				return $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2].$hex[3].$hex[3];
			case 6:
				return $hex.'ff';
		}
		return $hex;
	}

	public function getRgbObjFromHex($hex) {
		return new Rgb(
			$this->getHexArray(
				$this->extractHexCode($hex)
			)
		);
	}

	public function getRgbObjFromName($name) {
		return new Rgb(
			$this->getHexArray(
				$this->getHexFromName($name)
			)
		);
	}

	private function getHexArray($hex) {
		return [
			'red' => (hexdec(substr($hex, 0, 2)) / 255),
			'green' => (hexdec(substr($hex, 2, 2)) / 255),
			'blue' => (hexdec(substr($hex, 4, 2)) / 255),
			'alpha' => (hexdec(substr($hex, 6, 2)) / 255),
		];
	}

	public function normalizeValue($value, $max) {
		if (strpos($value, '%') !== false) {
			return max(0, min(1, (floatval($value) / 100)));
		}
		return max(0, min(1, (floatval($value) / $max)));
	}

	public function getColorObj($color) {
		$color = preg_replace('/[\s]*/', '', strtolower($color));
		if (empty($color) || (strpos($color, 'transparent') !== false)) {
			return null;
		}
		if ($color[0] === '#') {
			return $this->getRgbObjFromHex($color);
		}
		if ($color[0] === '[') {
			return $this->getColorObjFromJs($color);
		}
		$rex = '/^(t|g|rgba|rgb|hsla|hsl|cmyka|cmyk)[\(]/';
		if (preg_match($rex, $color, $col) === 1) {
			return $this->getColorObjFromCss($col[1], $color);
		}
		return $this->getRgbObjFromName($color);
	}

	public function getRgbSquareDistance($cola, $colb) {
		return (pow(($cola['red'] - $colb['red']), 2)
			+ pow(($cola['green'] - $colb['green']), 2)
			+ pow(($cola['blue'] - $colb['blue']), 2));
	}

	public function getClosestWebColor($col) {
		$color = '';
		$mindist = 3; // = 1^2 + 1^2 + 1^2
		foreach (self::$webhex as $name => $hex) {
			$dist = $this->getRgbSquareDistance($col, $this->getHexArray($hex));
			if ($dist <= $mindist) {
				$mindist = $dist;
				$color = $name;
			}
		}
		return $color;
	}
}

// SPOT

class Spot extends Web {
	protected static $default_spot_colors = array (
		'none' => array('name' => 'None',
			'color' => array('cyan' => 0, 'magenta' => 0, 'yellow' => 0, 'key' => 0, 'alpha' => 1)),
		'all' => array('name' => 'All',
			'color' => array('cyan' => 1, 'magenta' => 1, 'yellow' => 1, 'key' => 1, 'alpha' => 1)),
		'cyan' => array('name' => 'Cyan',
			'color' => array('cyan' => 1, 'magenta' => 0, 'yellow' => 0, 'key' => 0, 'alpha' => 1)),
		'magenta' => array('name' => 'Magenta',
			'color' => array('cyan' => 0, 'magenta' => 1, 'yellow' => 0, 'key' => 0, 'alpha' => 1)),
		'yellow' => array('name' => 'Yellow',
			'color' => array('cyan' => 0, 'magenta' => 0, 'yellow' => 1, 'key' => 0, 'alpha' => 1)),
		'key' => array('name' => 'Key',
			'color' => array('cyan' => 0, 'magenta' => 0, 'yellow' => 0, 'key' => 1, 'alpha' => 1)),
		'white' => array('name' => 'White',
			'color' => array('cyan' => 0, 'magenta' => 0, 'yellow' => 0, 'key' => 0, 'alpha' => 1)),
		'black' => array('name' => 'Black',
			'color' => array('cyan' => 0, 'magenta' => 0, 'yellow' => 0, 'key' => 1, 'alpha' => 1)),
		'red' => array('name' => 'Red',
			'color' => array('cyan' => 0, 'magenta' => 1, 'yellow' => 1, 'key' => 0, 'alpha' => 1)),
		'green' => array('name' => 'Green',
			'color' => array('cyan' => 1, 'magenta' => 0, 'yellow' => 1, 'key' => 0, 'alpha' => 1)),
		'blue' => array('name' => 'Blue',
			'color' => array('cyan' => 1, 'magenta' => 1, 'yellow' => 0, 'key' => 0, 'alpha' => 1)),
	);

	protected $spot_colors = array();

	public function getSpotColors() {
		return $this->spot_colors;
	}

	public function normalizeSpotColorName($name) {
		return preg_replace('/[^a-z0-9]*/', '', strtolower($name));
	}

	public function getSpotColor($name) {
		$key = $this->normalizeSpotColorName($name);
		if (empty($this->spot_colors[$key])) {
			if (empty(self::$default_spot_colors[$key])) {
				throw new ColorException('unable to find the spot color: '.$key);
			}
			$this->addSpotColor($key, new Cmyk(self::$default_spot_colors[$key]['color']));
		}
		return $this->spot_colors[$key];
	}

	public function getSpotColorObj($name) {
		$spot = $this->getSpotColor($name);
		return $spot['color'];
	}

	public function addSpotColor($name, Cmyk $color) {
		$key = $this->normalizeSpotColorName($name);
		$num = count($this->spot_colors);
		if (isset($this->spot_colors[$key])) {
			$num = $this->spot_colors[$key]['i'];
		}
		else {
			$num = 1 + count($this->spot_colors);
		}
		$this->spot_colors[$key] = [
			'i' => $num,
			'name' => $name,
			'color' => $color,
		];
	}
}

// PDF

class Pdf extends Spot {
	protected static $jscolor = [
		'transparent',
		'black',
		'white',
		'red',
		'green',
		'blue',
		'cyan',
		'magenta',
		'yellow',
		'dkGray',
		'gray',
		'ltGray',
	];

	public function getJsMap() {
		return self::$jscolor;
	}

	public function getJsColorString($color) {
		if (in_array($color, self::$jscolor)) {
			return 'color.' . $color;
		}
		$webcolor = new Web();
		try {
			if (($colobj = $webcolor->getColorObj($color)) !== null) {
				return $colobj->getJsPdfColor();
			}
		}
		catch (ColorException $e) {}

		return 'color.' . self::$jscolor[0];
	}

	public function getColorObject($color) {
		try {
			return $this->getSpotColorObj($color);
		}
		catch (ColorException $e) {}
		try {
			return $this->getColorObj($color);
		}
		catch (ColorException $e) {}
		return null;
	}
}

// TEMPLATE

interface Template {
	public function getArray();
	public function getNormalizedArray($max);
	public function getCssColor();
	public function getJsPdfColor();
	public function getPdfColor();
	public function toGrayArray();
	public function toRgbArray();
	public function toHslArray();
	public function toCmykArray();
	public function invertColor();
}

// COLOUR

abstract class Colour {
	protected $type;
	protected $cmp_alpha = 1.0;

	public function __construct($components) {
		foreach ($components as $color => $value) {
			$property = 'cmp_' . $color;
			if (property_exists($this, $property)) {
				$this->$property = (max(0, min(1, floatval($value))));
			}
		}
	}

	public function getType() {
		return $this->type;
	}

	public function getNormalizedValue($value, $max) {
		return round(max(0, min($max, ($max * floatval($value)))));
	}

	public function getHexValue($value, $max) {
		return sprintf('%02x', $this->getNormalizedValue($value, $max));
	}

	public function getRgbaHexColor() {
		$rgba = $this->toRgbArray();
		return '#'
			. $this->getHexValue($rgba['red'], 255)
			. $this->getHexValue($rgba['green'], 255)
			. $this->getHexValue($rgba['blue'], 255)
			. $this->getHexValue($rgba['alpha'], 255);
	}

	public function getRgbHexColor() {
		$rgba = $this->toRgbArray();
		return '#'
			. $this->getHexValue($rgba['red'], 255)
			. $this->getHexValue($rgba['green'], 255)
			. $this->getHexValue($rgba['blue'], 255);
	}
}

// RGB

class Rgb extends Colour implements Template {
	protected $type = 'RGB';
	protected $cmp_red = 0.0;
	protected $cmp_green = 0.0;
	protected $cmp_blue = 0.0;

	public function getArray() {
		return [
			'R' => $this->cmp_red,
			'G' => $this->cmp_green,
			'B' => $this->cmp_blue,
			'A' => $this->cmp_alpha
		];
	}

	public function getNormalizedArray($max) {
		return [
			'R' => $this->getNormalizedValue($this->cmp_red, $max),
			'G' => $this->getNormalizedValue($this->cmp_green, $max),
			'B' => $this->getNormalizedValue($this->cmp_blue, $max),
			'A' => $this->cmp_alpha
		];
	}

	public function getCssColor() {
		return 'rgba('
			. $this->getNormalizedValue($this->cmp_red, 100).'%,'
			. $this->getNormalizedValue($this->cmp_green, 100).'%,'
			. $this->getNormalizedValue($this->cmp_blue, 100).'%,'
			. $this->cmp_alpha
		. ')';
	}

	public function getJsPdfColor() {
		if ($this->cmp_alpha == 0) {
			return '["T"]';
		}
		return sprintf('["RGB",%F,%F,%F]', $this->cmp_red, $this->cmp_green, $this->cmp_blue);
	}

	public function getPdfColor() {
		return sprintf('%F %F %F', $this->cmp_red, $this->cmp_green, $this->cmp_blue);
	}

	public function toGrayArray() {
		return [
			'gray' => (max(0, min(
				1,
				((0.2126 * $this->cmp_red) + (0.7152 * $this->cmp_green) + (0.0722 * $this->cmp_blue))
			))),
			'alpha' => $this->cmp_alpha
		];
	}

	public function toRgbArray() {
		return [
			'red' => $this->cmp_red,
			'green' => $this->cmp_green,
			'blue' => $this->cmp_blue,
			'alpha' => $this->cmp_alpha
		];
	}

	public function toHslArray() {
		$min = min($this->cmp_red, $this->cmp_green, $this->cmp_blue);
		$max = max($this->cmp_red, $this->cmp_green, $this->cmp_blue);
		$lightness = (($min + $max) / 2);
		if ($min == $max) {
			$saturation = 0;
			$hue = 0;
		}
		else {
			$diff = ($max - $min);
			if ($lightness < 0.5) {
				$saturation = ($diff / ($max + $min));
			}
			else {
				$saturation = ($diff / (2.0 - $max - $min));
			}
			switch ($max) {
				case $this->cmp_red:
					$dgb = ($this->cmp_green - $this->cmp_blue);
					$hue = ($dgb / $diff) + (($dgb < 0) ? 6 : 0);
					break;
				case $this->cmp_green:
					$hue = (2.0 + (($this->cmp_blue - $this->cmp_red) / $diff));
					break;
				case $this->cmp_blue:
					$hue = (4.0 + (($this->cmp_red - $this->cmp_green) / $diff));
					break;
			}
			$hue /= 6; // 6 = 360 / 60
		}
		return [
			'hue' => max(0, min(1, $hue)),
			'saturation' => max(0, min(1, $saturation)),
			'lightness' => max(0, min(1, $lightness)),
			'alpha' => $this->cmp_alpha
		];
	}

	public function toCmykArray() {
		$cyan = (1 - $this->cmp_red);
		$magenta = (1 - $this->cmp_green);
		$yellow = (1 - $this->cmp_blue);
		$key = 1;
		if ($cyan < $key) {
			$key = $cyan;
		}
		if ($magenta < $key) {
			$key = $magenta;
		}
		if ($yellow < $key) {
			$key = $yellow;
		}
		if ($key == 1) {
			// black
			$cyan = 0;
			$magenta = 0;
			$yellow = 0;
		}
		else {
			$cyan = (($cyan - $key) / (1 - $key));
			$magenta = (($magenta - $key) / (1 - $key));
			$yellow = (($yellow - $key) / (1 - $key));
		}
		return [
			'cyan' => max(0, min(1, $cyan)),
			'magenta' => max(0, min(1, $magenta)),
			'yellow' => max(0, min(1, $yellow)),
			'key' => max(0, min(1, $key)),
			'alpha' => $this->cmp_alpha
		];
	}

	public function invertColor() {
		$this->cmp_red = (1 - $this->cmp_red);
		$this->cmp_green = (1 - $this->cmp_green);
		$this->cmp_blue = (1 - $this->cmp_blue);
	}
}

// GRAY

class Gray extends Colour implements Template {
	protected $type = 'GRAY';
	protected $cmp_gray = 0.0;

	public function getArray() {
		return [
			'G' => $this->cmp_gray,
			'A' => $this->cmp_alpha
		];
	}

	public function getNormalizedArray($max) {
		return [
			'G' => $this->getNormalizedValue($this->cmp_gray, $max),
			'A' => $this->cmp_alpha
		];
	}

	public function getCssColor() {
		return 'rgba('
			. $this->getNormalizedValue($this->cmp_gray, 100).'%,'
			. $this->getNormalizedValue($this->cmp_gray, 100).'%,'
			. $this->getNormalizedValue($this->cmp_gray, 100).'%,'
			. $this->cmp_alpha
		. ')';
	}

	public function getJsPdfColor() {
		if ($this->cmp_alpha == 0) {
			return '["T"]'; // transparent color
		}
		return sprintf('["G",%F]', $this->cmp_gray);
	}

	public function getPdfColor() {
		return sprintf('%F', $this->cmp_gray);
	}

	public function toGrayArray() {
		return [
			'gray' => $this->cmp_gray,
			'alpha' => $this->cmp_alpha
		];
	}

	public function toRgbArray() {
		return [
			'red' => $this->cmp_gray,
			'green' => $this->cmp_gray,
			'blue' => $this->cmp_gray,
			'alpha' => $this->cmp_alpha
		];
	}

	public function toHslArray() {
		return [
			'hue' => 0,
			'saturation' => 0,
			'lightness' => $this->cmp_gray,
			'alpha' => $this->cmp_alpha
		];
	}

	public function toCmykArray() {
		return [
			'cyan' => 0,
			'magenta' => 0,
			'yellow' => 0,
			'key' => $this->cmp_gray,
			'alpha' => $this->cmp_alpha
		];
	}

	public function invertColor() {
		$this->cmp_gray = (1 - $this->cmp_gray);
	}
}

// HSL

class Hsl extends Colour implements Template {
	protected $type = 'HSL';
	protected $cmp_hue = 0.0;
	protected $cmp_saturation = 0.0;
	protected $cmp_lightness = 0.0;

	public function getArray() {
		return [
			'H' => $this->cmp_hue,
			'S' => $this->cmp_saturation,
			'L' => $this->cmp_lightness,
			'A' => $this->cmp_alpha
		];
	}

	public function getNormalizedArray($max) {
		$max = 360;
		return [
			'H' => $this->getNormalizedValue($this->cmp_hue, $max),
			'S' => $this->cmp_saturation,
			'L' => $this->cmp_lightness,
			'A' => $this->cmp_alpha
		];
	}

	public function getCssColor() {
		return 'hsla('
			. $this->getNormalizedValue($this->cmp_hue, 360) . ','
			. $this->getNormalizedValue($this->cmp_saturation, 100) . '%,'
			. $this->getNormalizedValue($this->cmp_lightness, 100) . '%,'
			. $this->cmp_alpha
		. ')';
	}

	public function getJsPdfColor() {
		$rgb = $this->toRgbArray();
		if ($this->cmp_alpha == 0) {
			return '["T"]'; // transparent color
		}
		return sprintf('["RGB",%F,%F,%F]', $rgb['red'], $rgb['green'], $rgb['blue']);
	}

	public function getPdfColor() {
		$rgb = $this->toRgbArray();
		return sprintf('%F %F %F', $rgb['red'], $rgb['green'], $rgb['blue']);
	}

	public function toGrayArray() {
		return [
			'gray'  => $this->cmp_lightness,
			'alpha' => $this->cmp_alpha
		];
	}

	public function toRgbArray() {
		if ($this->cmp_saturation == 0) {
			return [
				'red'   => $this->cmp_lightness,
				'green' => $this->cmp_lightness,
				'blue'  => $this->cmp_lightness,
				'alpha' => $this->cmp_alpha
			];
		}
		if ($this->cmp_lightness < 0.5) {
			$valb = ($this->cmp_lightness * (1 + $this->cmp_saturation));
		}
		else {
			$valb = (($this->cmp_lightness + $this->cmp_saturation) - ($this->cmp_lightness * $this->cmp_saturation));
		}
		$vala = ((2 * $this->cmp_lightness) - $valb);
		return [
			'red' => $this->convertHuetoRgb($vala, $valb, ($this->cmp_hue + (1 / 3))),
			'green' => $this->convertHuetoRgb($vala, $valb, $this->cmp_hue),
			'blue' => $this->convertHuetoRgb($vala, $valb, ($this->cmp_hue - (1 / 3))),
			'alpha' => $this->cmp_alpha
		];
	}

	private function convertHuetoRgb($vala, $valb, $hue) {
		if ($hue < 0) {
			$hue += 1;
		}
		if ($hue > 1) {
			$hue -= 1;
		}
		if ((6 * $hue) < 1) {
			return max(0, min(1, ($vala + (($valb - $vala) * 6 * $hue))));
		}
		if ((2 * $hue) < 1) {
			return max(0, min(1, $valb));
		}
		if ((3 * $hue) < 2) {
			return max(0, min(1, ($vala + (($valb - $vala) * ((2 / 3) - $hue) * 6))));
		}
		return max(0, min(1, $vala));
	}

	public function toHslArray() {
		return [
			'hue' => $this->cmp_hue,
			'saturation' => $this->cmp_saturation,
			'lightness' => $this->cmp_lightness,
			'alpha' => $this->cmp_alpha
		];
	}

	public function toCmykArray()     {
		$rgb = new Rgb($this->toRgbArray());
		return $rgb->toCmykArray();
	}

	public function invertColor() {
		$this->cmp_hue = ($this->cmp_hue >= 0.5) ? ($this->cmp_hue - 0.5) : ($this->cmp_hue + 0.5);
	}
}

// CMYK

class Cmyk extends Colour implements Template {
	protected $type = 'CMYK';
	protected $cmp_cyan = 0.0;
	protected $cmp_magenta = 0.0;
	protected $cmp_yellow = 0.0;
	protected $cmp_key = 0.0;

	public function getArray() {
		return [
			'C' => $this->cmp_cyan,
			'M' => $this->cmp_magenta,
			'Y' => $this->cmp_yellow,
			'K' => $this->cmp_key,
			'A' => $this->cmp_alpha
		];
	}

	public function getNormalizedArray($max) {
		return [
			'C' => $this->getNormalizedValue($this->cmp_cyan, $max),
			'M' => $this->getNormalizedValue($this->cmp_magenta, $max),
			'Y' => $this->getNormalizedValue($this->cmp_yellow, $max),
			'K' => $this->getNormalizedValue($this->cmp_key, $max),
			'A' => $this->cmp_alpha,
		];
	}

	public function getCssColor() {
		$rgb = $this->toRgbArray();
		return 'rgba('
			. $this->getNormalizedValue($rgb['red'], 100) . '%,'
			. $this->getNormalizedValue($rgb['green'], 100) . '%,'
			. $this->getNormalizedValue($rgb['blue'], 100) . '%,'
			. $rgb['alpha']
		. ')';
	}

	public function getJsPdfColor() {
		if ($this->cmp_alpha == 0) {
			return '["T"]'; // transparent color
		}
		return sprintf('["CMYK",%F,%F,%F,%F]', $this->cmp_cyan, $this->cmp_magenta, $this->cmp_yellow, $this->cmp_key);
	}

	public function getPdfColor() {
		return sprintf('%F %F %F %F', $this->cmp_cyan, $this->cmp_magenta, $this->cmp_yellow, $this->cmp_key);
	}

	public function toGrayArray() {
		return [
			'gray' => $this->cmp_key,
			'alpha' => $this->cmp_alpha
		];
	}

	public function toRgbArray() {
		return [
			'red' => max(0, min(1, (1 - (($this->cmp_cyan    * (1 - $this->cmp_key)) + $this->cmp_key)))),
			'green' => max(0, min(1, (1 - (($this->cmp_magenta * (1 - $this->cmp_key)) + $this->cmp_key)))),
			'blue' => max(0, min(1, (1 - (($this->cmp_yellow  * (1 - $this->cmp_key)) + $this->cmp_key)))),
			'alpha' => $this->cmp_alpha
		];
	}

	public function toHslArray() {
		$rgb = new Rgb($this->toRgbArray());
		return $rgb->toHslArray();
	}

	public function toCmykArray() {
		return [
			'cyan' => $this->cmp_cyan,
			'magenta' => $this->cmp_magenta,
			'yellow' => $this->cmp_yellow,
			'key' => $this->cmp_key,
			'alpha' => $this->cmp_alpha
		];
	}

	public function invertColor() {
		$this->cmp_cyan = (1 - $this->cmp_cyan);
		$this->cmp_magenta = (1 - $this->cmp_magenta);
		$this->cmp_yellow = (1 - $this->cmp_yellow);
		$this->cmp_key = (1 - $this->cmp_key);
	}
}

class BarcodeException extends \Exception {}

// BARCODE

class Barcode {
	public const BARCODETYPES = [
		'C128' => 'CODE 128',
		'C128A' => 'CODE 128 A',
		'C128B' => 'CODE 128 B',
		'C128C' => 'CODE 128 C',
		'C39' => 'CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.',
		'C39+' => 'CODE 39 + CHECKSUM',
		'C39E' => 'CODE 39 EXTENDED',
		'C39E+' => 'CODE 39 EXTENDED + CHECKSUM',
		'C93' => 'CODE 93 - USS-93',
		'CODABAR' => 'CODABAR',
		'CODE11' => 'CODE 11',
		'EAN13' => 'EAN 13',
		'EAN2' => 'EAN 2-Digits UPC-Based Extension',
		'EAN5' => 'EAN 5-Digits UPC-Based Extension',
		'EAN8' => 'EAN 8',
		'I25' => 'Interleaved 2 of 5',
		'I25+' => 'Interleaved 2 of 5 + CHECKSUM',
		'IMB' => 'IMB - Intelligent Mail Barcode - Onecode - USPS-B-3200',
		'IMBPRE' => 'IMB - Intelligent Mail Barcode pre-processed',
		'KIX' => 'KIX (Klant index - Customer index)',
		'LRAW' => '1D RAW MODE (comma-separated rows of 01 strings)',
		'MSI' => 'MSI (Variation of Plessey code)',
		'MSI+' => 'MSI + CHECKSUM (modulo 11)',
		'PHARMA' => 'PHARMACODE',
		'PHARMA2T' => 'PHARMACODE TWO-TRACKS',
		'PLANET' => 'PLANET',
		'POSTNET' => 'POSTNET',
		'RMS4CC' => 'RMS4CC (Royal Mail 4-state Customer Bar Code)',
		'S25' => 'Standard 2 of 5',
		'S25+' => 'Standard 2 of 5 + CHECKSUM',
		'UPCA' => 'UPC-A',
		'UPCE' => 'UPC-E',
		'AZTEC' => 'AZTEC Code (ISO/IEC 24778:2008)',
		'DATAMATRIX' => 'DATAMATRIX (ISO/IEC 16022)',
		'PDF417' => 'PDF417 (ISO/IEC 15438:2006)',
		'QRCODE' => 'QR-CODE',
		'SRAW' => '2D RAW MODE (comma-separated rows of 01 strings)',
	];

	public function getBarcodeObj($type, $code, int $width = -1, int $height = -1, $color = 'black', array $padding = [0, 0, 0, 0]): Model {
		$params = explode(',', $type);
		$type = array_shift($params);

		$class = match($type) {
			'C128' => 'CodeOneTwoEight',
			'C128A' => 'CodeOneTwoEightA',
			'C128B' => 'CodeOneTwoEightB',
			'C128C' => 'CodeOneTwoEightC',
			'C39' => 'CodeThreeNine',
			'C39+' => 'CodeThreeNineCheck',
			'C39E' => 'CodeThreeNineExt',
			'C39E+' => 'CodeThreeNineExtCheck',
			'C93' => 'CodeNineThree',
			'CODABAR' => 'Codabar',
			'CODE11' => 'CodeOneOne',
			'EAN13' => 'EanOneThree',
			'EAN2' => 'EanTwo',
			'EAN5' => 'EanFive',
			'EAN8' => 'EanEight',
			'I25' => 'InterleavedTwoOfFive',
			'I25+' => 'InterleavedTwoOfFiveCheck',
			'IMB' => 'Imb',
			'IMBPRE' => 'ImbPre',
			'KIX' => 'KlantIndex',
			'LRAW' => 'LRaw',
			'MSI' => 'Msi',
			'MSI+' => 'MsiCheck',
			'PHARMA' => 'Pharma',
			'PHARMA2T' => 'PharmaTwoTracks',
			'PLANET' => 'Planet',
			'POSTNET' => 'Postnet',
			'RMS4CC' => 'RoyalMailFourCc',
			'S25' => 'StandardTwoOfFive',
			'S25+' => 'StandardTwoOfFiveCheck',
			'UPCA' => 'UpcA',
			'UPCE' => 'UpcE',
			'AZTEC' => 'Aztec',
			'DATAMATRIX' => 'Datamatrix',
			'PDF417' => 'PdfFourOneSeven',
			'QRCODE' => 'QrCode',
			'SRAW' => 'SRaw',
			default => throw new BarcodeException('Unsupported barcode type: ' . $type)
		};

		return new $class($code, $width, $height, $color, $params, $padding);
	}
}

// MODEL

interface Model {
	public function setSize(int $width, int $height, array $padding = [0, 0, 0, 0]): static;
	public function setColor(string $color): static;
	public function setBackgroundColor(string $color): static;
	public function getArray(): array;
	public function getExtendedCode(): string;
	public function getSvg(?string $filename = null): void;
	public function getInlineSvgCode(): string;
	public function getSvgCode(): string;
	public function getHtmlDiv(): string;
	public function getPng(?string $filename = null): void;
	public function getPngData(bool $imagick = true): string;
	public function getPngDataImagick(): string;
	public function getGd(): \GdImage;
	public function getGrid(string $space_char = '0', string $bar_char = '1'): string;
	public function getGridArray(string $space_char = '0', string $bar_char = '1'): array;
	public function getBarsArrayXYXY(): array;
	public function getBarsArrayXYWH(): array;
}

// CONVERT

abstract class Convert {
	protected const TYPE = '';
	protected const FORMAT = '';
	protected array $params = [];
	protected string $code = '';
	protected string $extcode = '';
	protected int $ncols = 0;
	protected int $nrows = 1;
	protected array $bars = [];
	protected int $width = 0;
	protected int $height = 0;

	protected array $padding = [
		'T' => 0,
		'R' => 0,
		'B' => 0,
		'L' => 0,
	];

	protected float $width_ratio = 0;
	protected float $height_ratio = 0;
	protected Colour $color_obj;
	protected ?Colour $bg_color_obj = null;

	protected function processBinarySequence(array $rows): void {
		if ($rows === []) {
			throw new BarcodeException('Empty input string');
		}

		$this->nrows = count($rows);
		$this->ncols = is_array($rows[0]) ? count($rows[0]) : strlen($rows[0]);

		if ($this->ncols === 0) {
			throw new BarcodeException('Empty columns');
		}

		$this->bars = [];
		foreach ($rows as $posy => $row) {
			if (! is_array($row)) {
				$row = str_split($row, 1);
			}

			$prevcol = '';
			$bar_width = 0;
			$row[] = '0';
			for ($posx = 0; $posx <= $this->ncols; ++$posx) {
				if ($row[$posx] != $prevcol) {
					if ($prevcol == '1') {
						$this->bars[] = [($posx - $bar_width), $posy, $bar_width, 1];
					}

					$bar_width = 0;
				}

				++$bar_width;
				$prevcol = $row[$posx];
			}
		}
	}

	protected function getRawCodeRows(string $data): array {
		$search = [
			'/[\s]*/s',    // remove spaces and newlines
			'/^[\[,]+/',   // remove trailing brackets or commas
			'/[\],]+$/',   // remove trailing brackets or commas
			'/[\]][\[]$/', // convert bracket -separated to comma-separated
		];

		$replace = ['', '', '', ''];

		$code = preg_replace($search, $replace, $data);
		if ($code === null) {
			throw new BarcodeException('Invalid input string');
		}

		return explode(',', $code);
	}

	protected function convertDecToHex(string $number): string {
		if ($number == 0) {
			return '00';
		}

		$hex = [];
		while ($number > 0) {
			$hex[] = strtoupper(dechex((int) bcmod($number, '16')));
			$number = bcdiv($number, '16', 0);
		}

		$hex = array_reverse($hex);
		return implode('', $hex);
	}

	protected function convertHexToDec(string $hex): string {
		$dec = '0';
		$bitval = '1';
		$len = strlen($hex);
		for ($pos = ($len - 1); $pos >= 0; --$pos) {
			$dec = bcadd($dec, bcmul((string) hexdec($hex[$pos]), $bitval));
			$bitval = bcmul($bitval, '16');
		}

		return $dec;
	}

	public function getGridArray(string $space_char = '0', string $bar_char = '1'): array {
		$raw = array_fill(0, $this->nrows, array_fill(0, $this->ncols, $space_char));
		foreach ($this->bars as $bar) {
			if ($bar[2] <= 0) {
				continue;
			}

			if ($bar[3] <= 0) {
				continue;
			}

			for ($vert = 0; $vert < $bar[3]; ++$vert) {
				for ($horiz = 0; $horiz < $bar[2]; ++$horiz) {
					$raw[($bar[1] + $vert)][($bar[0] + $horiz)] = $bar_char;
				}
			}
		}

		return $raw;
	}

	protected function getRotatedBarArray(): array {
		$grid = $this->getGridArray();
		$cols = array_map(null, ...$grid);
		$bars = [];
		foreach ($cols as $posx => $col) {
			$prevrow = '';
			$bar_height = 0;
			$col[] = '0';
			for ($posy = 0; $posy <= $this->nrows; ++$posy) {
				if ($col[$posy] != $prevrow) {
					if ($prevrow == '1') {
						$bars[] = [$posx, ($posy - $bar_height), 1, $bar_height];
					}

					$bar_height = 0;
				}

				++$bar_height;
				$prevrow = $col[$posy];
			}
		}

		return $bars;
	}

	protected function getBarRectXYXY(array $bar): array {
		return [
			($this->padding['L'] + ($bar[0] * $this->width_ratio)),
			($this->padding['T'] + ($bar[1] * $this->height_ratio)),
			($this->padding['L'] + (($bar[0] + $bar[2]) * $this->width_ratio) - 1),
			($this->padding['T'] + (($bar[1] + $bar[3]) * $this->height_ratio) - 1),
		];
	}

	protected function getBarRectXYWH(array $bar): array {
		return [
			($this->padding['L'] + ($bar[0] * $this->width_ratio)),
			($this->padding['T'] + ($bar[1] * $this->height_ratio)),
			($bar[2] * $this->width_ratio),
			($bar[3] * $this->height_ratio),
		];
	}
}

// TYPE

abstract class Type extends Convert implements Model {
	public function __construct($code, int $width = -1, int $height = -1, $color = 'black', array $params = [], array $padding = [0, 0, 0, 0]) {
		$this->code = $code;
		$this->extcode = $code;
		$this->params = $params;
		$this->setParameters();
		$this->setBars();
		$this->setSize($width, $height, $padding);
		$this->setColor($color);
	}

	protected function setParameters(): void {}

	protected function setBars(): void {}

	public function setSize(int $width, int $height, array $padding = [0, 0, 0, 0]): static {
		$this->width = $width;
		if ($this->width <= 0) {
			$this->width = (abs(min(-1, $this->width)) * $this->ncols);
		}

		$this->height = $height;
		if ($this->height <= 0) {
			$this->height = (abs(min(-1, $this->height)) * $this->nrows);
		}

		$this->width_ratio = ($this->width / $this->ncols);
		$this->height_ratio = ($this->height / $this->nrows);

		$this->setPadding($padding);

		return $this;
	}

	protected function setPadding(array $padding): static {
		if (count($padding) != 4) {
			throw new BarcodeException(
				'Invalid padding, expecting an array of 4 numbers (top, right, bottom, left)'
			);
		}

		$map = [
			['T', $this->height_ratio],
			['R', $this->width_ratio],
			['B', $this->height_ratio],
			['L', $this->width_ratio],
		];
		foreach ($padding as $key => $val) {
			if ($val < 0) {
				$val = (abs(min(-1, $val)) * $map[$key][1]);
			}

			$this->padding[$map[$key][0]] = (int) $val;
		}

		return $this;
	}

	public function setColor(string $color): static {
		$colobj = $this->getRgbColorObject($color);
		if (! $colobj instanceof Rgb) {
			throw new BarcodeException('The foreground color cannot be empty or transparent');
		}

		$this->color_obj = $colobj;
		return $this;
	}

	public function setBackgroundColor(string $color): static {
		$this->bg_color_obj = $this->getRgbColorObject($color);
		return $this;
	}

	protected function getRgbColorObject(string $color): ?Rgb {
		$pdf = new Pdf();
		$cobj = $pdf->getColorObject($color);
		if ($cobj instanceof Colour) {
			return new Rgb($cobj->toRgbArray());
		}

		return null;
	}

	public function getArray(): array {
		return [
			'type' => $this::TYPE,
			'format' => $this::FORMAT,
			'params' => $this->params,
			'code' => $this->code,
			'extcode' => $this->extcode,
			'ncols' => $this->ncols,
			'nrows' => $this->nrows,
			'width' => $this->width,
			'height' => $this->height,
			'width_ratio' => $this->width_ratio,
			'height_ratio' => $this->height_ratio,
			'padding' => $this->padding,
			'full_width' => ($this->width + $this->padding['L'] + $this->padding['R']),
			'full_height' => ($this->height + $this->padding['T'] + $this->padding['B']),
			'color_obj' => $this->color_obj,
			'bg_color_obj' => $this->bg_color_obj,
			'bars' => $this->bars,
		];
	}

	public function getExtendedCode(): string {
		return $this->extcode;
	}

	protected function getHTTPFile(string $data, string $mime, string $fileext, ?string $filename = null): void {
		if (is_null($filename) || (preg_match('/^[a-zA-Z0-9_\-]{1,250}$/', $filename) !== 1)) {
			$filename = md5($data);
		}

		header('Content-Type: ' . $mime);
		header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
		header('Pragma: public');
		header('Expires: Thu, 04 Jan 1973 00:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-Disposition: inline; filename="' . $filename . '.' . $fileext . '";');

		if (empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
			header('Content-Length: ' . strlen($data));
		}

		echo $data;
	}

	public function getSvg(?string $filename = null): void {
		$this->getHTTPFile($this->getSvgCode(), 'application/svg+xml', 'svg', $filename);
	}

	public function getInlineSvgCode(): string {
		$hflag = ENT_NOQUOTES;
		if (defined('ENT_XML1') && defined('ENT_DISALLOWED')) {
			$hflag = ENT_XML1 | ENT_DISALLOWED;
		}

		$width = sprintf('%F', ($this->width + $this->padding['L'] + $this->padding['R']));
		$height = sprintf('%F', ($this->height + $this->padding['T'] + $this->padding['B']));

		$svg = '<svg'
			. ' version="1.2"'
			. ' baseProfile="full"'
			. ' xmlns="http://www.w3.org/2000/svg"'
			. ' xmlns:xlink="http://www.w3.org/1999/xlink"'
			. ' xmlns:ev="http://www.w3.org/2001/xml-events"'
			. ' width="' . $width . '"'
			. ' height="' . $height . '"'
			. ' viewBox="0 0 ' . $width . ' ' . $height . '"'
			. '>' . "\n"
			. "\t" . '<desc>' . htmlspecialchars($this->code, $hflag, 'UTF-8') . '</desc>' . "\n";
		if ($this->bg_color_obj instanceof Rgb) {
			$svg .= '   <rect x="0" y="0" width="' . $width . '"'
				. ' height="' . $height . '"'
				. ' fill="' . $this->bg_color_obj->getRgbHexColor() . '"'
				. ' stroke="none"'
				. ' stroke-width="0"'
				. ' stroke-linecap="square"'
				. ' />' . "\n";
		}

		$svg .= '   <g id="bars" fill="' . $this->color_obj->getRgbHexColor() . '"'
			. ' stroke="none"'
			. ' stroke-width="0"'
			. ' stroke-linecap="square"'
			. '>' . "\n";
		$bars = $this->getBarsArrayXYWH();
		foreach ($bars as $bar) {
			$svg .= '       <rect x="' . sprintf('%F', $bar[0]) . '"'
				. ' y="' . sprintf('%F', $bar[1]) . '"'
				. ' width="' . sprintf('%F', $bar[2]) . '"'
				. ' height="' . sprintf('%F', $bar[3]) . '"'
				. ' />' . "\n";
		}

		return $svg . ('    </g>' . "\n"
			. '</svg>' . "\n");
	}

	public function getSvgCode(): string {
		return '<?xml version="1.0" standalone="no" ?>'
			. "\n"
			. $this->getInlineSvgCode();
	}

	public function getHtmlDiv(): string {
		$html = '<div style="width:' . sprintf('%F', ($this->width + $this->padding['L'] + $this->padding['R'])) . 'px;'
			. 'height:' . sprintf('%F', ($this->height + $this->padding['T'] + $this->padding['B'])) . 'px;'
			. 'position:relative;'
			. 'font-size:0;'
			. 'border:none;'
			. 'padding:0;'
			. 'margin:0;';
		if ($this->bg_color_obj instanceof Rgb) {
			$html .= 'background-color:' . $this->bg_color_obj->getCssColor() . ';';
		}

		$html .= '">' . "\n";
		$bars = $this->getBarsArrayXYWH();
		foreach ($bars as $bar) {
			$html .= '  <div style="background-color:' . $this->color_obj->getCssColor() . ';'
				. 'left:' . sprintf('%F', $bar[0]) . 'px;'
				. 'top:' . sprintf('%F', $bar[1]) . 'px;'
				. 'width:' . sprintf('%F', $bar[2]) . 'px;'
				. 'height:' . sprintf('%F', $bar[3]) . 'px;'
				. 'position:absolute;'
				. 'border:none;'
				. 'padding:0;'
				. 'margin:0;'
				. '">&nbsp;</div>' . "\n";
		}

		return $html . ('</div>' . "\n");
	}

	public function getPng(?string $filename = null): void {
		$this->getHTTPFile($this->getPngData(), 'image/png', 'png', $filename);
	}

	public function getPngData(bool $imagick = true): string {
		if ($imagick && extension_loaded('imagick')) {
			return $this->getPngDataImagick();
		}

		$gdImage = $this->getGd();
		ob_start();
		imagepng($gdImage);
		$data = ob_get_clean();
		if ($data === false) {
			throw new BarcodeException('Unable to get PNG data');
		}
		return $data;
	}

	public function getPngDataImagick(): string {
		$imagick = new \Imagick();
		$width = (int) ceil($this->width + $this->padding['L'] + $this->padding['R']);
		$height = (int) ceil($this->height + $this->padding['T'] + $this->padding['B']);
		$imagick->newImage($width, $height, 'none', 'png');
		$imagickdraw = new \imagickdraw();
		if ($this->bg_color_obj instanceof Rgb) {
			$rgbcolor = $this->bg_color_obj->getNormalizedArray(255);
			$bg_color = new \imagickpixel('rgb(' . $rgbcolor['R'] . ',' . $rgbcolor['G'] . ',' . $rgbcolor['B'] . ')');
			$imagickdraw->setfillcolor($bg_color);
			$imagickdraw->rectangle(0, 0, $width, $height);
		}

		$rgbcolor = $this->color_obj->getNormalizedArray(255);
		$bar_color = new \imagickpixel('rgb(' . $rgbcolor['R'] . ',' . $rgbcolor['G'] . ',' . $rgbcolor['B'] . ')');
		$imagickdraw->setfillcolor($bar_color);
		$bars = $this->getBarsArrayXYXY();
		foreach ($bars as $bar) {
			$imagickdraw->rectangle($bar[0], $bar[1], $bar[2], $bar[3]);
		}

		$imagick->drawimage($imagickdraw);
		return $imagick->getImageBlob();
	}

	public function getGd(): \GdImage {
		$width = max(1, (int) ceil($this->width + $this->padding['L'] + $this->padding['R']));
		$height = max(1, (int) ceil($this->height + $this->padding['T'] + $this->padding['B']));
		$img = imagecreate($width, $height);
		if ($img === false) {
			throw new BarcodeException('Unable to create GD image');
		}

		if (! $this->bg_color_obj instanceof Rgb) {
			$bgobj = clone $this->color_obj;
			$rgbcolor = $bgobj->invertColor()->getNormalizedArray(255);
			$background_color = imagecolorallocate(
				$img,
				(int) round($rgbcolor['R']),
				(int) round($rgbcolor['G']),
				(int) round($rgbcolor['B'])
			);
			if ($background_color === false) {
				throw new BarcodeException('Unable to allocate default GD background color');
			}
			imagecolortransparent($img, $background_color);
		}
		else {
			$rgbcolor = $this->bg_color_obj->getNormalizedArray(255);
			$bg_color = imagecolorallocate(
				$img,
				(int) round($rgbcolor['R']),
				(int) round($rgbcolor['G']),
				(int) round($rgbcolor['B'])
			);
			if ($bg_color === false) {
				throw new BarcodeException('Unable to allocate GD background color');
			}
			imagefilledrectangle($img, 0, 0, $width, $height, $bg_color);
		}

		$rgbcolor = $this->color_obj->getNormalizedArray(255);
		$bar_color = imagecolorallocate(
			$img,
			(int) round($rgbcolor['R']),
			(int) round($rgbcolor['G']),
			(int) round($rgbcolor['B'])
		);
		if ($bar_color === false) {
			throw new BarcodeException('Unable to allocate GD foreground color');
		}
		$bars = $this->getBarsArrayXYXY();
		foreach ($bars as $bar) {
			imagefilledrectangle(
				$img,
				(int) floor($bar[0]),
				(int) floor($bar[1]),
				(int) floor($bar[2]),
				(int) floor($bar[3]),
				$bar_color
			);
		}

		return $img;
	}

	public function getGrid(string $space_char = '0', string $bar_char = '1'): string {
		$raw = $this->getGridArray($space_char, $bar_char);
		$grid = '';
		foreach ($raw as $row) {
			$grid .= implode('', $row) . "\n";
		}

		return $grid;
	}

	public function getBarsArrayXYXY(): array {
		$rect = [];
		foreach ($this->bars as $bar) {
			if ($bar[2] <= 0) {
				continue;
			}

			if ($bar[3] <= 0) {
				continue;
			}

			$rect[] = $this->getBarRectXYXY($bar);
		}

		if ($this->nrows > 1) {
			// reprint rotated to cancel row gaps
			$rot = $this->getRotatedBarArray();
			foreach ($rot as $bar) {
				if ($bar[2] <= 0) {
					continue;
				}

				if ($bar[3] <= 0) {
					continue;
				}

				$rect[] = $this->getBarRectXYXY($bar);
			}
		}

		return $rect;
	}

	public function getBarsArrayXYWH(): array {
		$rect = [];
		foreach ($this->bars as $bar) {
			if ($bar[2] <= 0) {
				continue;
			}

			if ($bar[3] <= 0) {
				continue;
			}

			$rect[] = $this->getBarRectXYWH($bar);
		}

		if ($this->nrows > 1) {
			// reprint rotated to cancel row gaps
			$rot = $this->getRotatedBarArray();
			foreach ($rot as $bar) {
				if ($bar[2] <= 0) {
					continue;
				}

				if ($bar[3] <= 0) {
					continue;
				}

				$rect[] = $this->getBarRectXYWH($bar);
			}
		}

		return $rect;
	}
}

// LINEAR

abstract class Linear extends Type {
	protected const TYPE = 'linear';
}

// RAW

class Raw extends Type {
	protected function setBars(): void {
		$this->processBinarySequence($this->getRawCodeRows($this->code));
	}
}

// SQUARE

abstract class Square extends Type {
	protected const TYPE = 'square';
}

// DATA

class Data {
	public const QRSPEC_VERSION_MAX = 40;
	public const QRSPEC_WIDTH_MAX = 177;
	public const QRCAP_WIDTH = 0;
	public const QRCAP_WORDS = 1;
	public const QRCAP_REMINDER = 2;
	public const QRCAP_EC = 3;
	public const STRUCTURE_HEADER_BITS = 20;
	public const MAX_STRUCTURED_SYMBOLS = 16;
	public const N1 = 3;
	public const N2 = 3;
	public const N3 = 40;
	public const N4 = 10;

	public const ENC_MODES = [
		'NL' => -1,
		'NM' => 0,
		'AN' => 1,
		'8B' => 2,
		'KJ' => 3,
		'ST' => 4,
	];

	public const ECC_LEVELS = [
		'L' => 0,
		'M' => 1,
		'Q' => 2,
		'H' => 3,
	];

	public const AN_TABLE = [
		-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 36, -1, -1, -1, 37, 38, -1, -1, -1, -1, 39, 40, -1, 41, 42, 43, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 44, -1, -1, -1, -1, -1, -1, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1
	];

	public const CAPACITY = [
		[0, 0, 0, [0, 0, 0, 0]],
		[21, 26, 0, [7, 10, 13, 17]],
		[25, 44, 7, [10, 16, 22, 28]],
		[29, 70, 7, [15, 26, 36, 44]],
		[33, 100, 7, [20, 36, 52, 64]],
		[37, 134, 7, [26, 48, 72, 88]],
		[41, 172, 7, [36, 64, 96, 112]],
		[45, 196, 0, [40, 72, 108, 130]],
		[49, 242, 0, [48, 88, 132, 156]],
		[53, 292, 0, [60, 110, 160, 192]],
		[57, 346, 0, [72, 130, 192, 224]],
		[61, 404, 0, [80, 150, 224, 264]],
		[65, 466, 0, [96, 176, 260, 308]],
		[69, 532, 0, [104, 198, 288, 352]],
		[73, 581, 3, [120, 216, 320, 384]],
		[77, 655, 3, [132, 240, 360, 432]],
		[81, 733, 3, [144, 280, 408, 480]],
		[85, 815, 3, [168, 308, 448, 532]],
		[89, 901, 3, [180, 338, 504, 588]],
		[93, 991, 3, [196, 364, 546, 650]],
		[97, 1085, 3, [224, 416, 600, 700]],
		[101, 1156, 4, [224, 442, 644, 750]],
		[105, 1258, 4, [252, 476, 690, 816]],
		[109, 1364, 4, [270, 504, 750, 900]],
		[113, 1474, 4, [300, 560, 810, 960]],
		[117, 1588, 4, [312, 588, 870, 1050]],
		[121, 1706, 4, [336, 644, 952, 1110]],
		[125, 1828, 4, [360, 700, 1020, 1200]],
		[129, 1921, 3, [390, 728, 1050, 1260]],
		[133, 2051, 3, [420, 784, 1140, 1350]],
		[137, 2185, 3, [450, 812, 1200, 1440]],
		[141, 2323, 3, [480, 868, 1290, 1530]],
		[145, 2465, 3, [510, 924, 1350, 1620]],
		[149, 2611, 3, [540, 980, 1440, 1710]],
		[153, 2761, 3, [570, 1036, 1530, 1800]],
		[157, 2876, 0, [570, 1064, 1590, 1890]],
		[161, 3034, 0, [600, 1120, 1680, 1980]],
		[165, 3196, 0, [630, 1204, 1770, 2100]],
		[169, 3362, 0, [660, 1260, 1860, 2220]],
		[173, 3532, 0, [720, 1316, 1950, 2310]],
		[177, 3706, 0, [750, 1372, 2040, 2430]],
	];

	public const LEN_TABLE_BITS = [
		[10, 12, 14],
		[9, 11, 13],
		[8, 16, 16],
		[8, 10, 12],
	];

	public const ECC_TABLE = [
		[[0, 0], [0, 0], [0, 0], [0, 0]],
		[[1, 0], [1, 0], [1, 0], [1, 0]],
		[[1, 0], [1, 0], [1, 0], [1, 0]],
		[[1, 0], [1, 0], [2, 0], [2, 0]],
		[[1, 0], [2, 0], [2, 0], [4, 0]],
		[[1, 0], [2, 0], [2, 2], [2, 2]],
		[[2, 0], [4, 0], [4, 0], [4, 0]],
		[[2, 0], [4, 0], [2, 4], [4, 1]],
		[[2, 0], [2, 2], [4, 2], [4, 2]],
		[[2, 0], [3, 2], [4, 4], [4, 4]],
		[[2, 2], [4, 1], [6, 2], [6, 2]],
		[[4, 0], [1, 4], [4, 4], [3, 8]],
		[[2, 2], [6, 2], [4, 6], [7, 4]],
		[[4, 0], [8, 1], [8, 4], [12, 4]],
		[[3, 1], [4, 5], [11, 5], [11, 5]],
		[[5, 1], [5, 5], [5, 7], [11, 7]],
		[[5, 1], [7, 3], [15, 2], [3, 13]],
		[[1, 5], [10, 1], [1, 15], [2, 17]],
		[[5, 1], [9, 4], [17, 1], [2, 19]],
		[[3, 4], [3, 11], [17, 4], [9, 16]],
		[[3, 5], [3, 13], [15, 5], [15, 10]],
		[[4, 4], [17, 0], [17, 6], [19, 6]],
		[[2, 7], [17, 0], [7, 16], [34, 0]],
		[[4, 5], [4, 14], [11, 14], [16, 14]],
		[[6, 4], [6, 14], [11, 16], [30, 2]],
		[[8, 4], [8, 13], [7, 22], [22, 13]],
		[[10, 2], [19, 4], [28, 6], [33, 4]],
		[[8, 4], [22, 3], [8, 26], [12, 28]],
		[[3, 10], [3, 23], [4, 31], [11, 31]],
		[[7, 7], [21, 7], [1, 37], [19, 26]],
		[[5, 10], [19, 10], [15, 25], [23, 25]],
		[[13, 3], [2, 29], [42, 1], [23, 28]],
		[[17, 0], [10, 23], [10, 35], [19, 35]],
		[[17, 1], [14, 21], [29, 19], [11, 46]],
		[[13, 6], [14, 23], [44, 7], [59, 1]],
		[[12, 7], [12, 26], [39, 14], [22, 41]],
		[[6, 14], [6, 34], [46, 10], [2, 64]],
		[[17, 4], [29, 14], [49, 10], [24, 46]],
		[[4, 18], [13, 32], [48, 14], [42, 32]],
		[[20, 4], [40, 7], [43, 22], [10, 67]],
		[[19, 6], [18, 31], [34, 34], [20, 61]],
	];

	public const ALIGN_PATTERN = [
		[0, 0],
		[0, 0],
		[18, 0],
		[22, 0],
		[26, 0],
		[30, 0],
		[34, 0],
		[22, 38],
		[24, 42],
		[26, 46],
		[28, 50],
		[30, 54],
		[32, 58],
		[34, 62],
		[26, 46],
		[26, 48],
		[26, 50],
		[30, 54],
		[30, 56],
		[30, 58],
		[34, 62],
		[28, 50],
		[26, 50],
		[30, 54],
		[28, 54],
		[32, 58],
		[30, 58],
		[34, 62],
		[26, 50],
		[30, 54],
		[26, 52],
		[30, 56],
		[34, 60],
		[30, 58],
		[34, 62],
		[30, 54],
		[24, 50],
		[28, 54],
		[32, 58],
		[26, 54],
		[30, 58],
	];

	public const VERSION_PATTERN = [
		0x07c94,
		0x085bc,
		0x09a99,
		0x0a4d3,
		0x0bbf6,
		0x0c762,
		0x0d847,
		0x0e60d,
		0x0f928,
		0x10b78,
		0x1145d,
		0x12a17,
		0x13532,
		0x149a6,
		0x15683,
		0x168c9,
		0x177ec,
		0x18ec4,
		0x191e1,
		0x1afab,
		0x1b08e,
		0x1cc1a,
		0x1d33f,
		0x1ed75,
		0x1f250,
		0x209d5,
		0x216f0,
		0x228ba,
		0x2379f,
		0x24b0b,
		0x2542e,
		0x26a64,
		0x27541,
		0x28c69,
	];

	public const FORMAT_INFO = [
		[0x77c4, 0x72f3, 0x7daa, 0x789d, 0x662f, 0x6318, 0x6c41, 0x6976],
		[0x5412, 0x5125, 0x5e7c, 0x5b4b, 0x45f9, 0x40ce, 0x4f97, 0x4aa0],
		[0x355f, 0x3068, 0x3f31, 0x3a06, 0x24b4, 0x2183, 0x2eda, 0x2bed],
		[0x1689, 0x13be, 0x1ce7, 0x19d0, 0x0762, 0x0255, 0x0d0c, 0x083b],
	];
}

// ESTIMATE

abstract class Estimate {
	protected int $hint = 2;
	public int $version = 0;
	protected int $level = 0;

	public function getLengthIndicator(int $mode, int $version): int {
		if ($mode == Data::ENC_MODES['ST']) {
			return 0;
		}

		if ($version <= 9) {
			$len = 0;
		}
		elseif ($version <= 26) {
			$len = 1;
		}
		else {
			$len = 2;
		}

		return Data::LEN_TABLE_BITS[$mode][$len];
	}

	public function estimateBitsModeNum(int $size): int {
		$wdt = (int) ($size / 3);
		$bits = ($wdt * 10);
		match ($size - ($wdt * 3)) {
			1 => $bits += 4,
			2 => $bits += 7,
			default => $bits,
		};
		return $bits;
	}

	public function estimateBitsModeAn(int $size): int {
		$bits = (int) ($size * 5.5);
		if (($size & 1) !== 0) {
			$bits += 6;
		}

		return $bits;
	}

	public function estimateBitsMode8(int $size): int {
		return $size * 8;
	}

	public function estimateBitsModeKanji(int $size): int {
		return (int) ($size * 6.5);
	}

	public function estimateVersion(array $items, int $level): int {
		$version = 0;
		$prev = 0;
		do {
			$prev = $version;
			$bits = $this->estimateBitStreamSize($items, $prev);
			$version = $this->getMinimumVersion((int) (($bits + 7) / 8), $level);
			if ($version < 0) {
				return -1;
			}
		} while ($version > $prev);

		return $version;
	}

	protected function getMinimumVersion(int $size, int $level): int {
		for ($idx = 1; $idx <= Data::QRSPEC_VERSION_MAX; ++$idx) {
			$words = (Data::CAPACITY[$idx][Data::QRCAP_WORDS] - Data::CAPACITY[$idx][Data::QRCAP_EC][$level]);
			if ($words >= $size) {
				return $idx;
			}
		}

		throw new BarcodeException(
			'The size of input data is greater than Data::QR capacity, try to lower the error correction mode'
		);
	}

	protected function estimateBitStreamSize(array $items, int $version): int {
		$bits = 0;
		if ($version == 0) {
			$version = 1;
		}

		foreach ($items as $item) {
			switch ($item['mode']) {
				case Data::ENC_MODES['NM']:
					$bits = $this->estimateBitsModeNum($item['size']);
					break;
				case Data::ENC_MODES['AN']:
					$bits = $this->estimateBitsModeAn($item['size']);
					break;
				case Data::ENC_MODES['8B']:
					$bits = $this->estimateBitsMode8($item['size']);
					break;
				case Data::ENC_MODES['KJ']:
					$bits = $this->estimateBitsModeKanji($item['size']);
					break;
				case Data::ENC_MODES['ST']:
					return Data::STRUCTURE_HEADER_BITS;
				default:
					return 0;
			}

			$len = $this->getLengthIndicator($item['mode'], $version);
			$mod = 1 << $len;
			$num = (int) (($item['size'] + $mod - 1) / $mod);
			$bits += $num * (4 + $len);
		}

		return $bits;
	}
}

// INPUTITEM

abstract class InputItem extends Estimate {
	public function lookAnTable(int $chr): int {
		return (($chr > 127) ? -1 : Data::AN_TABLE[$chr]);
	}

	public function appendNewInputItem(array $items, int $mode, int $size, array $data): array {
		$newitem = $this->newInputItem($mode, $size, $data);
		if ($newitem !== []) {
			$items[] = $newitem;
		}

		return $items;
	}

	protected function newInputItem(int $mode, int $size, array $data, array $bstream = []): array {
		$setData = array_slice($data, 0, $size);
		if (count($setData) < $size) {
			$setData = array_merge($setData, array_fill(0, ($size - count($setData)), '0'));
		}

		if (! $this->check($mode, $size, $setData)) {
			throw new BarcodeException('Invalid input item');
		}

		return [
			'mode' => $mode,
			'size' => $size,
			'data' => $setData,
			'bstream' => $bstream,
		];
	}

	protected function check(int $mode, int $size, array $data): bool {
		if ($size <= 0) {
			return false;
		}

		return match ($mode) {
			Data::ENC_MODES['NM'] => $this->checkModeNum($size, $data),
			Data::ENC_MODES['AN'] => $this->checkModeAn($size, $data),
			Data::ENC_MODES['KJ'] => $this->checkModeKanji($size, $data),
			Data::ENC_MODES['8B'] => true,
			Data::ENC_MODES['ST'] => true,
			default => false,
		};
	}

	protected function checkModeNum(int $size, array $data): bool {
		for ($idx = 0; $idx < $size; ++$idx) {
			if ((ord($data[$idx]) < ord('0')) || (ord($data[$idx]) > ord('9'))) {
				return false;
			}
		}

		return true;
	}

	protected function checkModeAn(int $size, array $data): bool {
		for ($idx = 0; $idx < $size; ++$idx) {
			if ($this->lookAnTable(ord($data[$idx])) == -1) {
				return false;
			}
		}

		return true;
	}

	protected function checkModeKanji(int $size, array $data): bool {
		if (($size & 1) !== 0) {
			return false;
		}

		for ($idx = 0; $idx < $size; $idx += 2) {
			$val = (ord($data[$idx]) << 8) | ord($data[($idx + 1)]);
			if (($val < 0x8140) || (($val > 0x9ffc) && ($val < 0xe040)) || ($val > 0xebbf)) {
				return false;
			}
		}

		return true;
	}
}

// ENCODINGMODE

abstract class EncodingMode extends InputItem {

	public function getEncodingMode(string $data, int $pos): int {
		if (! isset($data[$pos])) {
			return Data::ENC_MODES['NL'];
		}

		if ($this->isDigitAt($data, $pos)) {
			return Data::ENC_MODES['NM'];
		}

		if ($this->isAlphanumericAt($data, $pos)) {
			return Data::ENC_MODES['AN'];
		}

		return $this->getEncodingModeKj($data, $pos);
	}

	protected function getEncodingModeKj(string $data, int $pos): int {
		if (($this->hint == Data::ENC_MODES['KJ']) && isset($data[($pos + 1)])) {
			$word = ((ord($data[$pos]) << 8) | ord($data[($pos + 1)]));
			if ((($word >= 0x8140) && ($word <= 0x9ffc)) || (($word >= 0xe040) && ($word <= 0xebbf))) {
				return Data::ENC_MODES['KJ'];
			}
		}

		return Data::ENC_MODES['8B'];
	}

	public function isDigitAt(string $str, int $pos): bool {
		if (! isset($str[$pos])) {
			return false;
		}

		return ((ord($str[$pos]) >= ord('0')) && (ord($str[$pos]) <= ord('9')));
	}

	public function isAlphanumericAt(string $str, int $pos): bool {
		if (! isset($str[$pos])) {
			return false;
		}

		return ($this->lookAnTable(ord($str[$pos])) >= 0);
	}

	protected function appendBitstream(array $bitstream, array $append): array {
		if (count($append) == 0) {
			return $bitstream;
		}

		if (count($bitstream) == 0) {
			return $append;
		}

		return array_values(array_merge($bitstream, $append));
	}

	protected function appendNum(array $bitstream, int $bits, int $num): array {
		if ($bits == 0) {
			return [];
		}

		return $this->appendBitstream($bitstream, $this->newFromNum($bits, $num));
	}

	protected function appendBytes(array $bitstream, int $size, array $data): array {
		if ($size == 0) {
			return [];
		}

		return $this->appendBitstream($bitstream, $this->newFromBytes($size, $data));
	}

	protected function newFromNum(int $bits, int $num): array {
		$bstream = $this->allocate($bits);
		$mask = 1 << ($bits - 1);
		for ($idx = 0; $idx < $bits; ++$idx) {
			$bstream[$idx] = ($num & $mask) !== 0 ? 1 : 0;

			$mask >>= 1;
		}

		return $bstream;
	}

	protected function newFromBytes(int $size, array $data): array {
		$bstream = $this->allocate($size * 8);
		$pval = 0;
		for ($idx = 0; $idx < $size; ++$idx) {
			$mask = 0x80;
			for ($jdx = 0; $jdx < 8; ++$jdx) {
				$bstream[$pval] = ($data[$idx] & $mask) !== 0 ? 1 : 0;

				++$pval;
				$mask >>= 1;
			}
		}

		return $bstream;
	}

	protected function allocate(int $setLength): array {
		return array_fill(0, $setLength, 0);
	}
}

// ENCODE

abstract class Encode extends EncodingMode {
	protected function encodeModeNum(array $inputitem, int $version): array {
		$words = (int) ($inputitem['size'] / 3);
		$inputitem['bstream'] = [];
		$val = 0x1;
		$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 4, $val);
		$inputitem['bstream'] = $this->appendNum(
			$inputitem['bstream'],
			$this->getLengthIndicator(Data::ENC_MODES['NM'], $version),
			$inputitem['size']
		);

		for ($i = 0; $i < $words; ++$i) {
			$val = (ord($inputitem['data'][$i * 3]) - ord('0')) * 100;
			$val += (ord($inputitem['data'][$i * 3 + 1]) - ord('0')) * 10;
			$val += (ord($inputitem['data'][$i * 3 + 2]) - ord('0'));
			$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 10, $val);
		}

		if ($inputitem['size'] - $words * 3 == 1) {
			$val = ord($inputitem['data'][$words * 3]) - ord('0');
			$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 4, $val);
		}
		elseif (($inputitem['size'] - ($words * 3)) == 2) {
			$val = (ord($inputitem['data'][$words * 3]) - ord('0')) * 10;
			$val += (ord($inputitem['data'][$words * 3 + 1]) - ord('0'));
			$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 7, $val);
		}

		return $inputitem;
	}

	protected function encodeModeAn(array $inputitem, int $version): array {
		$words = (int) ($inputitem['size'] / 2);
		$inputitem['bstream'] = [];
		$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 4, 0x02);
		$inputitem['bstream'] = $this->appendNum(
			$inputitem['bstream'],
			$this->getLengthIndicator(Data::ENC_MODES['AN'], $version),
			$inputitem['size']
		);
		for ($idx = 0; $idx < $words; ++$idx) {
			$val = $this->lookAnTable(ord($inputitem['data'][($idx * 2)])) * 45;
			$val += $this->lookAnTable(ord($inputitem['data'][($idx * 2) + 1]));
			$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 11, $val);
		}

		if (($inputitem['size'] & 1) !== 0) {
			$val = $this->lookAnTable(ord($inputitem['data'][($words * 2)]));
			$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 6, $val);
		}

		return $inputitem;
	}

	protected function encodeMode8(array $inputitem, int $version): array {
		$inputitem['bstream'] = [];
		$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 4, 0x4);
		$inputitem['bstream'] = $this->appendNum(
			$inputitem['bstream'],
			$this->getLengthIndicator(Data::ENC_MODES['8B'], $version),
			$inputitem['size']
		);
		for ($idx = 0; $idx < $inputitem['size']; ++$idx) {
			$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 8, ord($inputitem['data'][$idx]));
		}

		return $inputitem;
	}

	protected function encodeModeKanji(array $inputitem, int $version): array {
		$inputitem['bstream'] = [];
		$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 4, 0x8);
		$inputitem['bstream'] = $this->appendNum(
			$inputitem['bstream'],
			$this->getLengthIndicator(Data::ENC_MODES['KJ'], $version),
			(int) ($inputitem['size'] / 2)
		);
		for ($idx = 0; $idx < $inputitem['size']; $idx += 2) {
			$val = (ord($inputitem['data'][$idx]) << 8) | ord($inputitem['data'][($idx + 1)]);
			if ($val <= 0x9ffc) {
				$val -= 0x8140;
			}
			else {
				$val -= 0xc140;
			}

			$val = ($val & 0xff) + (($val >> 8) * 0xc0);
			$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 13, $val);
		}

		return $inputitem;
	}

	protected function encodeModeStructure(array $inputitem): array {
		$inputitem['bstream'] = [];
		$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 4, 0x03);
		$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 4, ord($inputitem['data'][1]) - 1);
		$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 4, ord($inputitem['data'][0]) - 1);
		$inputitem['bstream'] = $this->appendNum($inputitem['bstream'], 8, ord($inputitem['data'][2]));
		return $inputitem;
	}
}

// BYTESTREAM

class ByteStream extends Encode {
	public function __construct(int $hint, int $version, int $level) {
		$this->hint = $hint;
		$this->version = $version;
		$this->level = $level;
	}

	public function getByteStream(array $items): array {
		return $this->bitstreamToByte(
			$this->appendPaddingBit(
				$this->mergeBitStream($items)
			)
		);
	}

	protected function mergeBitStream(array $items): array {
		$items = $this->convertData($items);
		$bstream = [];
		foreach ($items as $item) {
			$bstream = $this->appendBitstream($bstream, $item['bstream']);
		}

		return $bstream;
	}

	protected function appendPaddingBit(array $bstream): array {
		if (empty($bstream)) {
			return [];
		}

		$bits = count($bstream);
		$spec = new Spec();
		$maxwords = $spec->getDataLength($this->version, $this->level);
		$maxbits = $maxwords * 8;
		if ($maxbits == $bits) {
			return $bstream;
		}

		if ($maxbits - $bits < 5) {
			return $this->appendNum($bstream, $maxbits - $bits, 0);
		}

		$bits += 4;
		$words = (int) (($bits + 7) / 8);
		$padding = [];
		$padding = $this->appendNum($padding, $words * 8 - $bits + 4, 0);

		$padlen = $maxwords - $words;
		if ($padlen > 0) {
			$padbuf = [];
			for ($idx = 0; $idx < $padlen; ++$idx) {
				$padbuf[$idx] = ((($idx & 1) !== 0) ? 0x11 : 0xec);
			}

			$padding = $this->appendBytes($padding, $padlen, $padbuf);
		}

		return $this->appendBitstream($bstream, $padding);
	}

	protected function bitstreamToByte(array $bstream): array {
		$size = count($bstream);
		if ($size == 0) {
			return [];
		}

		$data = array_fill(0, (int) (($size + 7) / 8), 0);
		$bytes = (int) ($size / 8);
		$pos = 0;
		for ($idx = 0; $idx < $bytes; ++$idx) {
			$val = 0;
			for ($jdx = 0; $jdx < 8; ++$jdx) {
				$val <<= 1;
				$val |= $bstream[$pos];
				++$pos;
			}

			$data[$idx] = $val;
		}

		if (($size & 7) !== 0) {
			$val = 0;
			for ($jdx = 0; $jdx < ($size & 7); ++$jdx) {
				$val <<= 1;
				$val |= $bstream[$pos];
				++$pos;
			}

			$data[$bytes] = $val;
		}

		return $data;
	}

	protected function convertData(array $items): array {
		$ver = $this->estimateVersion($items, $this->level);
		if ($ver > $this->version) {
			$this->version = $ver;
		}

		while (true) {
			$cbs = $this->createBitStream($items);
			$items = $cbs[0];
			$bits = $cbs[1];
			if ($bits < 0) {
				throw new BarcodeException('Negative Bits value');
			}

			$ver = $this->getMinimumVersion((int) (($bits + 7) / 8), $this->level);
			if ($ver > $this->version) {
				$this->version = $ver;
			}
			else {
				break;
			}
		}

		return $items;
	}

	protected function createBitStream(array $items): array {
		$total = 0;
		foreach ($items as $key => $item) {
			$items[$key] = $this->encodeBitStream($item, $this->version);
			$bits = count($items[$key]['bstream']);
			$total += $bits;
		}

		return [$items, $total];
	}

	public function encodeBitStream(array $inputitem, int $version): array {
		$inputitem['bstream'] = [];
		$spec = new Spec();
		$words = $spec->maximumWords($inputitem['mode'], $version);

		if ($inputitem['size'] <= $words) {
			return match ($inputitem['mode']) {
				Data::ENC_MODES['NM'] => $this->encodeModeNum($inputitem, $version),
				Data::ENC_MODES['AN'] => $this->encodeModeAn($inputitem, $version),
				Data::ENC_MODES['8B'] => $this->encodeMode8($inputitem, $version),
				Data::ENC_MODES['KJ'] => $this->encodeModeKanji($inputitem, $version),
				Data::ENC_MODES['ST'] => $this->encodeModeStructure($inputitem),
				default => throw new BarcodeException('Invalid mode'),
			};
		}

		$st1 = $this->newInputItem($inputitem['mode'], $words, $inputitem['data']);
		$st2 = $this->newInputItem(
			$inputitem['mode'],
			($inputitem['size'] - $words),
			array_slice($inputitem['data'], $words)
		);
		$st1 = $this->encodeBitStream($st1, $version);
		$st2 = $this->encodeBitStream($st2, $version);
		$inputitem['bstream'] = [];
		$inputitem['bstream'] = $this->appendBitstream($inputitem['bstream'], $st1['bstream']);
		$inputitem['bstream'] = $this->appendBitstream($inputitem['bstream'], $st2['bstream']);

		return $inputitem;
	}
}

// MASKNUM

abstract class MaskNum {
	protected function makeMaskNo(int $maskNo, int $width, array $frame, array &$mask): int {
		$bnum = 0;
		$bitMask = $this->generateMaskNo($maskNo, $width, $frame);
		$mask = $frame;
		for ($ypos = 0; $ypos < $width; ++$ypos) {
			for ($xpos = 0; $xpos < $width; ++$xpos) {
				if ($bitMask[$ypos][$xpos] == 1) {
					$mask[$ypos][$xpos] = chr(ord($frame[$ypos][$xpos]) ^ ((int) ($bitMask[$ypos][$xpos])));
				}

				$bnum += ord($mask[$ypos][$xpos]) & 1;
			}
		}

		return $bnum;
	}

	protected function generateMaskNo(int $maskNo, int $width, array $frame): array {
		$bitMask = array_fill(0, $width, array_fill(0, $width, 0));
		for ($ypos = 0; $ypos < $width; ++$ypos) {
			for ($xpos = 0; $xpos < $width; ++$xpos) {
				if ((ord($frame[$ypos][$xpos]) & 0x80) !== 0) {
					$bitMask[$ypos][$xpos] = 0;
					continue;
				}
				$maskFunc = match ($maskNo) {
					0 => (($xpos + $ypos) & 1),
					1 => ($ypos & 1),
					2 => ($xpos % 3),
					3 => (($xpos + $ypos) % 3),
					4 => ((((int) ($ypos / 2)) + ((int) ($xpos / 3))) & 1),
					5 => ((($xpos * $ypos) & 1) + ($xpos * $ypos) % 3),
					6 => (((($xpos * $ypos) & 1) + ($xpos * $ypos) % 3) & 1),
					7 => (((($xpos * $ypos) % 3) + (($xpos + $ypos) & 1)) & 1),
					default => 1,
				};
				$bitMask[$ypos][$xpos] = (($maskFunc == 0) ? 1 : 0);
			}
		}

		return $bitMask;
	}
}

// MASK

abstract class Mask extends MaskNum {
	protected array $runLength = [];
	protected Spec $spc;

	public function __construct(public int $version, protected int $level, protected int $qr_find_from_random = -1, protected bool $qr_find_best_mask = true, protected int $qr_default_mask = 2) {
		$this->spc = new Spec();
	}

	protected function mask(int $width, array $frame, int $level): array {
		$minDemerit = PHP_INT_MAX;
		$bestMask = [];
		$checked_masks = [0, 1, 2, 3, 4, 5, 6, 7];
		if ($this->qr_find_from_random >= 0) {
			$howManuOut = (8 - ($this->qr_find_from_random % 9));
			for ($idx = 0; $idx < $howManuOut; ++$idx) {
				$maxpos = (count($checked_masks) - 1);
				$remPos = ($maxpos > 0) ? random_int(0, $maxpos) : 0;
				unset($checked_masks[$remPos]);
				$checked_masks = array_values($checked_masks);
			}
		}

		$bestMask = $frame;
		foreach ($checked_masks as $checked_mask) {
			$mask = array_fill(0, $width, str_repeat("\0", $width));
			$demerit = 0;
			$blacks = $this->makeMaskNo($checked_mask, $width, $frame, $mask);
			$blacks += $this->writeFormatInformation($width, $mask, $checked_mask, $level);
			$blacks = (int) (100 * $blacks / ($width * $width));
			$demerit = (int) (abs($blacks - 50) / 5) * Data::N4;
			$demerit += $this->evaluateSymbol($width, $mask);
			if ($demerit < $minDemerit) {
				$minDemerit = $demerit;
				$bestMask = $mask;
			}
		}

		return $bestMask;
	}

	protected function makeMask(int $width, array $frame, int $maskNo, int $level): array {
		$mask = [];
		$this->makeMaskNo($maskNo, $width, $frame, $mask);
		$this->writeFormatInformation($width, $mask, $maskNo, $level);
		return $mask;
	}

	protected function writeFormatInformation(int $width, array &$frame, int $maskNo, int $level): int {
		$blacks = 0;
		$spec = new Spec();
		$format = $spec->getFormatInfo($maskNo, $level);
		for ($idx = 0; $idx < 8; ++$idx) {
			if (($format & 1) !== 0) {
				$blacks += 2;
				$val = 0x85;
			}
			else {
				$val = 0x84;
			}

			$frame[8][($width - 1 - $idx)] = chr($val);
			if ($idx < 6) {
				$frame[$idx][8] = chr($val);
			}
			else {
				$frame[($idx + 1)][8] = chr($val);
			}

			$format >>= 1;
		}

		for ($idx = 0; $idx < 7; ++$idx) {
			if (($format & 1) !== 0) {
				$blacks += 2;
				$val = 0x85;
			}
			else {
				$val = 0x84;
			}

			$frame[($width - 7 + $idx)][8] = chr($val);
			if ($idx == 0) {
				$frame[8][7] = chr($val);
			}
			else {
				$frame[8][(6 - $idx)] = chr($val);
			}

			$format >>= 1;
		}

		return $blacks;
	}

	protected function evaluateSymbol(int $width, array $frame): int {
		$frameY = $frame[0];
		$frameYM = $frame[0];
		for ($ypos = 0; $ypos < $width; ++$ypos) {
			$frameY = $frame[$ypos];
			$frameYM = $ypos > 0 ? $frame[($ypos - 1)] : $frameY;
		}

		$demerit = $this->evaluateSymbolB($ypos, $width, $frameY, $frameYM);
		for ($xpos = 0; $xpos < $width; ++$xpos) {
			$head = 0;
			$this->runLength[0] = 1;
			for ($ypos = 0; $ypos < $width; ++$ypos) {
				if (($ypos == 0) && (ord($frame[$ypos][$xpos]) & 1)) {
					$this->runLength[0] = -1;
					$head = 1;
					$this->runLength[$head] = 1;
				}
				elseif ($ypos > 0) {
					if (((ord($frame[$ypos][$xpos]) ^ ord($frame[($ypos - 1)][$xpos])) & 1) !== 0) {
						++$head;
						$this->runLength[$head] = 1;
					}
					else {
						++$this->runLength[$head];
					}
				}
			}

			$demerit += $this->calcN1N3($head + 1);
		}

		return $demerit;
	}

	protected function evaluateSymbolB(int $ypos, int $width, string $frameY, string $frameYM): int {
		$head = 0;
		$demerit = 0;
		$this->runLength[0] = 1;
		for ($xpos = 0; $xpos < $width; ++$xpos) {
			if (($xpos > 0) && ($ypos > 0)) {
				$b22 = ord($frameY[$xpos])
					& ord($frameY[($xpos - 1)])
					& ord($frameYM[$xpos])
					& ord($frameYM[($xpos - 1)]);
				$w22 = ord($frameY[$xpos])
					| ord($frameY[($xpos - 1)])
					| ord($frameYM[$xpos])
					| ord($frameYM[($xpos - 1)]);
				if ((($b22 | ($w22 ^ 1)) & 1) !== 0) {
					$demerit += Data::N2;
				}
			}

			if (($xpos == 0) && (ord($frameY[$xpos]) & 1)) {
				$this->runLength[0] = -1;
				$head = 1;
				$this->runLength[$head] = 1;
			}
			elseif ($xpos > 0) {
				if (((ord($frameY[$xpos]) ^ ord($frameY[($xpos - 1)])) & 1) !== 0) {
					++$head;
					$this->runLength[$head] = 1;
				}
				else {
					++$this->runLength[$head];
				}
			}
		}

		return ($demerit + $this->calcN1N3($head + 1));
	}

	protected function calcN1N3(int $length): int {
		$demerit = 0;
		for ($idx = 0; $idx < $length; ++$idx) {
			if ($this->runLength[$idx] >= 5) {
				$demerit += (Data::N1 + ($this->runLength[$idx] - 5));
			}

			if (($idx & 1) && ($idx >= 3) && ($idx < ($length - 2)) && ($this->runLength[$idx] % 3 == 0)) {
				$demerit += $this->calcN1N3delta($length, $idx);
			}
		}

		return $demerit;
	}

	protected function calcN1N3delta(int $length, int $idx): int {
		$fact = (int) ($this->runLength[$idx] / 3);
		if (($this->runLength[($idx - 2)] == $fact) && ($this->runLength[($idx - 1)] == $fact) && ($this->runLength[($idx + 1)] == $fact) && ($this->runLength[($idx + 2)] == $fact)) {
			if (($this->runLength[($idx - 3)] < 0) || ($this->runLength[($idx - 3)] >= (4 * $fact))) {
				return Data::N3;
			}

			if ((($idx + 3) >= $length) || ($this->runLength[($idx + 3)] >= (4 * $fact))) {
				return Data::N3;
			}
		}

		return 0;
	}
}

// INIT

abstract class Init extends Mask {
	protected array $datacode = [];
	protected array $ecccode = [];
	protected int $blocks;
	protected array $rsblocks = [];
	protected int $count;
	protected int $dataLength;
	protected int $eccLength;
	protected int $bv1;
	protected int $width;
	protected array $frame = [];
	protected int $xpos;
	protected int $ypos;
	protected int $dir;
	protected int $bit;
	protected array $rsitems = [];

	protected function init(array $spec): void {
		$dlv = $this->spc->rsDataCodes1($spec);
		$elv = $this->spc->rsEccCodes1($spec);
		$rsv = $this->initRs(8, 0x11d, 0, 1, $elv, 255 - $dlv - $elv);
		$blockNo = 0;
		$dataPos = 0;
		$eccPos = 0;
		$ecc = [];
		$endfor = $this->spc->rsBlockNum1($spec);
		$this->initLoop($endfor, $dlv, $elv, $rsv, $eccPos, $blockNo, $dataPos, $ecc);
		if ($this->spc->rsBlockNum2($spec) == 0) {
			return;
		}

		$dlv = $this->spc->rsDataCodes2($spec);
		$elv = $this->spc->rsEccCodes2($spec);
		$rsv = $this->initRs(8, 0x11d, 0, 1, $elv, 255 - $dlv - $elv);
		if ($rsv == null) {
			throw new BarcodeException('Empty RS');
		}

		$endfor = $this->spc->rsBlockNum2($spec);
		$this->initLoop($endfor, $dlv, $elv, $rsv, $eccPos, $blockNo, $dataPos, $ecc);
	}

	protected function initLoop(int $endfor, int $dlv, int $elv, array $rsv, int &$eccPos, int &$blockNo, int &$dataPos, array &$ecc): void {
		for ($idx = 0; $idx < $endfor; ++$idx) {
			$data = array_slice($this->datacode, $dataPos);
			$ecc = array_slice($this->ecccode, $eccPos);
			$ecc = $this->encodeRsChar($rsv, $data, $ecc);
			$this->rsblocks[$blockNo] = [
				'data' => $data,
				'dataLength' => $dlv,
				'ecc' => $ecc,
				'eccLength' => $elv,
			];
			$this->ecccode = array_merge(array_slice($this->ecccode, 0, $eccPos), $ecc);
			$dataPos += $dlv;
			$eccPos += $elv;
			++$blockNo;
		}
	}

	protected function initRs(int $symsize, int $gfpoly, int $fcr, int $prim, int $nroots, int $pad): array {
		foreach ($this->rsitems as $rsv) {
			if ($rsv['pad'] != $pad) {
				continue;
			}

			if ($rsv['nroots'] != $nroots) {
				continue;
			}

			if ($rsv['mm'] != $symsize) {
				continue;
			}

			if ($rsv['gfpoly'] != $gfpoly) {
				continue;
			}

			if ($rsv['fcr'] != $fcr) {
				continue;
			}

			if ($rsv['prim'] != $prim) {
				continue;
			}

			return $rsv;
		}

		$rsv = $this->initRsChar($symsize, $gfpoly, $fcr, $prim, $nroots, $pad);
		array_unshift($this->rsitems, $rsv);
		return $rsv;
	}

	protected function modnn(array $rsv, int $xpos): int {
		while ($xpos >= $rsv['nn']) {
			$xpos -= $rsv['nn'];
			$xpos = (($xpos >> $rsv['mm']) + ($xpos & $rsv['nn']));
		}

		return $xpos;
	}

	protected function checkRsCharParamsA(int $symsize, int $fcr, int $prim): void {
		$shfsymsize = (1 << $symsize);
		if (($symsize < 0) || ($symsize > 8) || ($fcr < 0) || ($fcr >= $shfsymsize) || ($prim <= 0) || ($prim >= $shfsymsize)) {
			throw new BarcodeException('Invalid parameters');
		}
	}

	protected function checkRsCharParamsB(int $symsize, int $nroots, int $pad): void {
		$shfsymsize = (1 << $symsize);
		if (($nroots < 0) || ($nroots >= $shfsymsize) || ($pad < 0) || ($pad >= ($shfsymsize - 1 - $nroots))) {
			throw new BarcodeException('Invalid parameters');
		}
	}

	protected function initRsChar(int $symsize, int $gfpoly, int $fcr, int $prim, int $nroots, int $pad): array {
		$this->checkRsCharParamsA($symsize, $fcr, $prim);
		$this->checkRsCharParamsB($symsize, $nroots, $pad);
		$rsv = [];
		$rsv['mm'] = $symsize;
		$rsv['nn'] = ((1 << $symsize) - 1);
		$rsv['pad'] = $pad;
		$rsv['alpha_to'] = array_fill(0, ($rsv['nn'] + 1), 0);
		$rsv['index_of'] = array_fill(0, ($rsv['nn'] + 1), 0);
		$nnv = &$rsv['nn'];
		$azv = &$nnv;
		$rsv['index_of'][0] = $azv; // log(zero) = -inf
		$rsv['alpha_to'][$azv] = 0; // alpha**-inf = 0
		$srv = 1;
		for ($idx = 0; $idx < $rsv['nn']; ++$idx) {
			$rsv['index_of'][$srv] = $idx;
			$rsv['alpha_to'][$idx] = $srv;
			$srv <<= 1;
			if (($srv & (1 << $symsize)) !== 0) {
				$srv ^= $gfpoly;
			}

			$srv &= $rsv['nn'];
		}

		if ($srv != 1) {
			throw new BarcodeException('field generator polynomial is not primitive!');
		}

		$rsv['genpoly'] = array_fill(0, ($nroots + 1), 0);
		$rsv['fcr'] = $fcr;
		$rsv['prim'] = $prim;
		$rsv['nroots'] = $nroots;
		$rsv['gfpoly'] = $gfpoly;
		for ($iprim = 1; $iprim % $prim != 0; $iprim += $rsv['nn']) {
			; // intentional 
		}

		$rsv['iprim'] = (int) ($iprim / $prim);
		$rsv['genpoly'][0] = 1;
		for ($idx = 0, $root = ($fcr * $prim); $idx < $nroots; ++$idx, $root += $prim) {
			$rsv['genpoly'][($idx + 1)] = 1;
			for ($jdx = $idx; $jdx > 0; --$jdx) {
				if ($rsv['genpoly'][$jdx] != 0) {
					$rsv['genpoly'][$jdx] = ($rsv['genpoly'][($jdx - 1)]
						^ $rsv['alpha_to'][$this->modnn($rsv, $rsv['index_of'][$rsv['genpoly'][$jdx]] + $root)]);
				} else {
					$rsv['genpoly'][$jdx] = $rsv['genpoly'][($jdx - 1)];
				}
			}

			$rsv['genpoly'][0] = $rsv['alpha_to'][$this->modnn($rsv, $rsv['index_of'][$rsv['genpoly'][0]] + $root)];
		}

		for ($idx = 0; $idx <= $nroots; ++$idx) {
			$rsv['genpoly'][$idx] = $rsv['index_of'][$rsv['genpoly'][$idx]];
		}

		return $rsv;
	}

	protected function encodeRsChar(array $rsv, array $data, array $parity): array {
		$nnv = &$rsv['nn'];
		$alphato = &$rsv['alpha_to'];
		$indexof = &$rsv['index_of'];
		$genpoly = &$rsv['genpoly'];
		$nroots = &$rsv['nroots'];
		$pad = &$rsv['pad'];
		$azv = &$nnv;
		$parity = array_fill(0, $nroots, 0);
		for ($idx = 0; $idx < ($nnv - $nroots - $pad); ++$idx) {
			$feedback = $indexof[$data[$idx] ^ $parity[0]];
			if ($feedback != $azv) {
				$feedback = $this->modnn($rsv, ($nnv - $genpoly[$nroots] + $feedback));
				for ($jdx = 1; $jdx < $nroots; ++$jdx) {
					$parity[$jdx] ^= $alphato[$this->modnn($rsv, $feedback + $genpoly[($nroots - $jdx)])];
				}
			}

			array_shift($parity);
			$parity[] = $feedback != $azv ? $alphato[$this->modnn($rsv, $feedback + $genpoly[0])] : 0;
		}

		return $parity;
	}
}

class Encoder extends Init {
	public function encodeMask(int $maskNo, array $datacode): array {
		$this->datacode = $datacode;
		$spec = $this->spc->getEccSpec($this->version, $this->level, [0, 0, 0, 0, 0]);
		$this->bv1 = $this->spc->rsBlockNum1($spec);
		$this->dataLength = $this->spc->rsDataLength($spec);
		$this->eccLength = $this->spc->rsEccLength($spec);
		$this->ecccode = array_fill(0, $this->eccLength, 0);
		$this->blocks = $this->spc->rsBlockNum($spec);
		$this->init($spec);
		$this->count = 0;
		$this->width = $this->spc->getWidth($this->version);
		$this->frame = $this->spc->createFrame($this->version);
		$this->xpos = ($this->width - 1);
		$this->ypos = ($this->width - 1);
		$this->dir = -1;
		$this->bit = -1;

		for ($idx = 0; $idx < ($this->dataLength + $this->eccLength); ++$idx) {
			$code = $this->getCode();
			$bit = 0x80;
			for ($jdx = 0; $jdx < 8; ++$jdx) {
				$addr = $this->getNextPosition();
				$this->setFrameAt($addr, 0x02 | (($bit & $code) != 0));
				$bit >>= 1;
			}
		}

		$rbits = $this->spc->getRemainder($this->version);
		for ($idx = 0; $idx < $rbits; ++$idx) {
			$addr = $this->getNextPosition();
			$this->setFrameAt($addr, 0x02);
		}

		$this->runLength = array_fill(0, (Data::QRSPEC_WIDTH_MAX + 1), 0);
		if ($maskNo < 0) {
			if ($this->qr_find_best_mask) {
				$mask = $this->mask($this->width, $this->frame, $this->level);
			}
			else {
				$mask = $this->makeMask($this->width, $this->frame, ($this->qr_default_mask % 8), $this->level);
			}
		}
		else {
			$mask = $this->makeMask($this->width, $this->frame, $maskNo, $this->level);
		}

		if ($mask == null) {
			throw new BarcodeException('Null Mask');
		}

		return $mask;
	}

	protected function getCode(): int {
		if ($this->count < $this->dataLength) {
			$row = ($this->count % $this->blocks);
			$col = floor($this->count / $this->blocks);
			if ($col >= $this->rsblocks[0]['dataLength']) {
				$row += $this->bv1;
			}

			$ret = $this->rsblocks[$row]['data'][$col];
		}
		elseif ($this->count < ($this->dataLength + $this->eccLength)) {
			$row = (($this->count - $this->dataLength) % $this->blocks);
			$col = floor(($this->count - $this->dataLength) / $this->blocks);
			$ret = $this->rsblocks[$row]['ecc'][$col];
		}
		else {
			return 0;
		}

		++$this->count;
		return $ret;
	}

	protected function setFrameAt(array $pos, int $val): void {
		$this->frame[$pos['y']][$pos['x']] = chr($val);
	}

	protected function getNextPosition(): array {
		do {
			if ($this->bit == -1) {
				$this->bit = 0;
				return [
					'x' => $this->xpos,
					'y' => $this->ypos,
				];
			}

			$xpos = $this->xpos;
			$ypos = $this->ypos;
			$wdt = $this->width;
			$this->getNextPositionB($xpos, $ypos, $wdt);
			if (($xpos < 0) || ($ypos < 0)) {
				throw new BarcodeException('Error getting next position');
			}

			$this->xpos = $xpos;
			$this->ypos = $ypos;
		} while (ord($this->frame[$ypos][$xpos]) & 0x80);

		return [
			'x' => $xpos,
			'y' => $ypos,
		];
	}

	protected function getNextPositionB(int &$xpos, int &$ypos, int $wdt): void {
		if ($this->bit == 0) {
			--$xpos;
			++$this->bit;
		}
		else {
			++$xpos;
			$ypos += $this->dir;
			--$this->bit;
		}

		if ($this->dir < 0) {
			if ($ypos < 0) {
				$ypos = 0;
				$xpos -= 2;
				$this->dir = 1;
				if ($xpos == 6) {
					--$xpos;
					$ypos = 9;
				}
			}
		}
		elseif ($ypos === $wdt) {
			$ypos = $wdt - 1;
			$xpos -= 2;
			$this->dir = -1;
			if ($xpos == 6) {
				--$xpos;
				$ypos -= 8;
			}
		}
	}
}

// SPECRS

abstract class SpecRs {
	public function rsBlockNum(array $spec): int {
		return ($spec[0] + $spec[3]);
	}

	public function rsBlockNum1(array $spec): int {
		return $spec[0];
	}

	public function rsDataCodes1(array $spec): int {
		return $spec[1];
	}

	public function rsEccCodes1(array $spec): int {
		return $spec[2];
	}

	public function rsBlockNum2(array $spec): int {
		return $spec[3];
	}

	public function rsDataCodes2(array $spec): int {
		return $spec[4];
	}

	public function rsEccCodes2(array $spec): int {
		return $spec[2];
	}

	public function rsDataLength(array $spec): int {
		return ($spec[0] * $spec[1]) + ($spec[3] * $spec[4]);
	}

	public function rsEccLength(array $spec): int {
		return ($spec[0] + $spec[3]) * $spec[2];
	}

	public function createFrame(int $version): array {
		$width = Data::CAPACITY[$version][Data::QRCAP_WIDTH];
		$frameLine = str_repeat("\0", $width);
		$frame = array_fill(0, $width, $frameLine);
		$frame = $this->putFinderPattern($frame, 0, 0);
		$frame = $this->putFinderPattern($frame, $width - 7, 0);
		$frame = $this->putFinderPattern($frame, 0, $width - 7);
		$yOffset = $width - 7;
		for ($ypos = 0; $ypos < 7; ++$ypos) {
			$frame[$ypos][7] = "\xc0";
			$frame[$ypos][$width - 8] = "\xc0";
			$frame[$yOffset][7] = "\xc0";
			++$yOffset;
		}

		$setPattern = str_repeat("\xc0", 8);
		$frame = $this->qrstrset($frame, 0, 7, $setPattern);
		$frame = $this->qrstrset($frame, $width - 8, 7, $setPattern);
		$frame = $this->qrstrset($frame, 0, $width - 8, $setPattern);
		$setPattern = str_repeat("\x84", 9);
		$frame = $this->qrstrset($frame, 0, 8, $setPattern);
		$frame = $this->qrstrset($frame, $width - 8, 8, $setPattern, 8);

		$yOffset = $width - 8;
		for ($ypos = 0; $ypos < 8; ++$ypos, ++$yOffset) {
			$frame[$ypos][8] = "\x84";
			$frame[$yOffset][8] = "\x84";
		}

		$wdo = $width - 15;
		for ($idx = 1; $idx < $wdo; ++$idx) {
			$frame[6][(7 + $idx)] = chr(0x90 | ($idx & 1));
			$frame[(7 + $idx)][6] = chr(0x90 | ($idx & 1));
		}

		$frame = $this->putAlignmentPattern($version, $frame, $width);
		if ($version >= 7) {
			$vinf = $this->getVersionPattern($version);
			$val = $vinf;
			for ($xpos = 0; $xpos < 6; ++$xpos) {
				for ($ypos = 0; $ypos < 3; ++$ypos) {
					$frame[(($width - 11) + $ypos)][$xpos] = chr(0x88 | ($val & 1));
					$val >>= 1;
				}
			}

			$val = $vinf;
			for ($ypos = 0; $ypos < 6; ++$ypos) {
				for ($xpos = 0; $xpos < 3; ++$xpos) {
					$frame[$ypos][($xpos + ($width - 11))] = chr(0x88 | ($val & 1));
					$val >>= 1;
				}
			}
		}

		$frame[$width - 8][8] = "\x81";
		return $frame;
	}

	public function qrstrset(array $srctab, int $xpos, int $ypos, string $repl, ?int $replLen = null): array {
		$srctab[$ypos] = substr_replace(
			$srctab[$ypos],
			($replLen !== null) ? substr($repl, 0, $replLen) : $repl,
			$xpos,
			$replLen ?? strlen($repl)
		);
		return $srctab;
	}

	public function putAlignmentMarker(array $frame, int $pox, int $poy): array {
		$finder = [
			"\xa1\xa1\xa1\xa1\xa1",
			"\xa1\xa0\xa0\xa0\xa1",
			"\xa1\xa0\xa1\xa0\xa1",
			"\xa1\xa0\xa0\xa0\xa1",
			"\xa1\xa1\xa1\xa1\xa1",
		];
		$yStart = $poy - 2;
		$xStart = $pox - 2;
		for ($ydx = 0; $ydx < 5; ++$ydx) {
			$frame = $this->qrstrset($frame, $xStart, ($yStart + $ydx), $finder[$ydx]);
		}

		return $frame;
	}

	public function putFinderPattern(array $frame, int $pox, int $poy): array {
		$finder = [
			"\xc1\xc1\xc1\xc1\xc1\xc1\xc1",
			"\xc1\xc0\xc0\xc0\xc0\xc0\xc1",
			"\xc1\xc0\xc1\xc1\xc1\xc0\xc1",
			"\xc1\xc0\xc1\xc1\xc1\xc0\xc1",
			"\xc1\xc0\xc1\xc1\xc1\xc0\xc1",
			"\xc1\xc0\xc0\xc0\xc0\xc0\xc1",
			"\xc1\xc1\xc1\xc1\xc1\xc1\xc1",
		];
		for ($ypos = 0; $ypos < 7; ++$ypos) {
			$frame = $this->qrstrset($frame, $pox, ($poy + $ypos), $finder[$ypos]);
		}

		return $frame;
	}

	public function getVersionPattern(int $version): int {
		if (($version < 7) || ($version > Data::QRSPEC_VERSION_MAX)) {
			return 0;
		}

		return Data::VERSION_PATTERN[($version - 7)];
	}

	public function putAlignmentPattern(int $version, array $frame, int $width): array {
		if ($version < 2) {
			return $frame;
		}

		$dval = Data::ALIGN_PATTERN[$version][1] - Data::ALIGN_PATTERN[$version][0];
		if ($dval < 0) {
			$wdt = 2;
		}
		else {
			$wdt = (int) (($width - Data::ALIGN_PATTERN[$version][0]) / $dval + 2);
		}

		if ($wdt * $wdt - 3 == 1) {
			$psx = Data::ALIGN_PATTERN[$version][0];
			$psy = Data::ALIGN_PATTERN[$version][0];
			return $this->putAlignmentMarker($frame, $psx, $psy);
		}

		$cpx = Data::ALIGN_PATTERN[$version][0];
		$wdo = $wdt - 1;
		for ($xpos = 1; $xpos < $wdo; ++$xpos) {
			$frame = $this->putAlignmentMarker($frame, 6, $cpx);
			$frame = $this->putAlignmentMarker($frame, $cpx, 6);
			$cpx += $dval;
		}

		$cpy = Data::ALIGN_PATTERN[$version][0];
		for ($y = 0; $y < $wdo; ++$y) {
			$cpx = Data::ALIGN_PATTERN[$version][0];
			for ($xpos = 0; $xpos < $wdo; ++$xpos) {
				$frame = $this->putAlignmentMarker($frame, $cpx, $cpy);
				$cpx += $dval;
			}

			$cpy += $dval;
		}

		return $frame;
	}
}

// SPEC

class Spec extends SpecRs {
	public function getDataLength(int $version, int $level): int {
		return (Data::CAPACITY[$version][Data::QRCAP_WORDS] - Data::CAPACITY[$version][Data::QRCAP_EC][$level]);
	}

	public function getECCLength(int $version, int $level): int {
		return Data::CAPACITY[$version][Data::QRCAP_EC][$level];
	}

	public function getWidth(int $version): int {
		return Data::CAPACITY[$version][Data::QRCAP_WIDTH];
	}

	public function getRemainder(int $version): int {
		return Data::CAPACITY[$version][Data::QRCAP_REMINDER];
	}

	public function maximumWords(int $mode, int $version): int {
		if ($mode == Data::ENC_MODES['ST']) {
			return 3;
		}

		if ($version <= 9) {
			$lval = 0;
		}
		elseif ($version <= 26) {
			$lval = 1;
		}
		else {
			$lval = 2;
		}

		$bits = Data::LEN_TABLE_BITS[$mode][$lval];
		$words = (1 << $bits) - 1;
		if ($mode == Data::ENC_MODES['KJ']) {
			$words *= 2;
		}

		return $words;
	}

	public function getEccSpec(int $version, int $level, array $spec): array {
		if (count($spec) < 5) {
			$spec = [0, 0, 0, 0, 0];
		}

		$bv1 = Data::ECC_TABLE[$version][$level][0];
		$bv2 = Data::ECC_TABLE[$version][$level][1];
		$data = $this->getDataLength($version, $level);
		$ecc = $this->getECCLength($version, $level);
		if ($bv2 === 0) {
			$spec[0] = $bv1;
			$spec[1] = (int) ($data / $bv1);
			$spec[2] = (int) ($ecc / $bv1);
			$spec[3] = 0;
			$spec[4] = 0;
		}
		else {
			$spec[0] = $bv1;
			$spec[1] = (int) ($data / ($bv1 + $bv2));
			$spec[2] = (int) ($ecc / ($bv1 + $bv2));
			$spec[3] = $bv2;
			$spec[4] = $spec[1] + 1;
		}

		return $spec;
	}

	public function getFormatInfo(int $maskNo, int $level): int {
		if (($maskNo < 0) || ($maskNo > 7) || ($level < 0) || ($level > 3)) {
			return 0;
		}

		return Data::FORMAT_INFO[$level][$maskNo];
	}
}

// SPLIT

class Split {
	protected array $items = [];

	public function __construct(protected EncodingMode $encodingMode, protected int $hint, protected int $version) {}

	public function getSplittedString(string $data): array {
		while (strlen($data) > 0) {
			$mode = $this->encodingMode->getEncodingMode($data, 0);
			switch ($mode) {
				case Data::ENC_MODES['NM']:
					$length = $this->eatNum($data);
					break;
				case Data::ENC_MODES['AN']:
					$length = $this->eatAn($data);
					break;
				case Data::ENC_MODES['KJ']:
					if ($this->hint == Data::ENC_MODES['KJ']) {
						$length = $this->eatKanji($data);
					}
					else {
						$length = $this->eat8($data);
					}

					break;
				default:
					$length = $this->eat8($data);
					break;
			}

			if ($length == 0) {
				break;
			}

			if ($length < 0) {
				throw new BarcodeException('Error while splitting the input data');
			}

			$data = substr($data, $length);
		}

		return $this->items;
	}

	protected function eatNum(string $data): int {
		$lng = $this->encodingMode->getLengthIndicator(Data::ENC_MODES['NM'], $this->version);
		$pos = 0;
		while ($this->encodingMode->isDigitAt($data, $pos)) {
			++$pos;
		}

		$mode = $this->encodingMode->getEncodingMode($data, $pos);
		if ($mode == Data::ENC_MODES['8B']) {
			$dif = $this->encodingMode->estimateBitsModeNum($pos) + 4 + $lng
				+ $this->encodingMode->estimateBitsMode8(1)         // + 4 + l8
				- $this->encodingMode->estimateBitsMode8($pos + 1); // - 4 - l8
			if ($dif > 0) {
				return $this->eat8($data);
			}
		}

		if ($mode == Data::ENC_MODES['AN']) {
			$dif = $this->encodingMode->estimateBitsModeNum($pos) + 4 + $lng
				+ $this->encodingMode->estimateBitsModeAn(1)        // + 4 + la
				- $this->encodingMode->estimateBitsModeAn($pos + 1); // - 4 - la
			if ($dif > 0) {
				return $this->eatAn($data);
			}
		}

		$this->items = $this->encodingMode->appendNewInputItem(
			$this->items,
			Data::ENC_MODES['NM'],
			$pos,
			str_split($data)
		);
		return $pos;
	}

	protected function eatAn(string $data): int {
		$lag = $this->encodingMode->getLengthIndicator(Data::ENC_MODES['AN'], $this->version);
		$lng = $this->encodingMode->getLengthIndicator(Data::ENC_MODES['NM'], $this->version);
		$pos = 1;
		while ($this->encodingMode->isAlphanumericAt($data, $pos)) {
			if ($this->encodingMode->isDigitAt($data, $pos)) {
				$qix = $pos;
				while ($this->encodingMode->isDigitAt($data, $qix)) {
					++$qix;
				}

				$dif = $this->encodingMode->estimateBitsModeAn($pos) // + 4 + lag
					+ $this->encodingMode->estimateBitsModeNum($qix - $pos) + 4 + $lng
					- $this->encodingMode->estimateBitsModeAn($qix); // - 4 - la
				if ($dif < 0) {
					break;
				}
				else {
					$pos = $qix;
				}
			}
			else {
				++$pos;
			}
		}

		if (! $this->encodingMode->isAlphanumericAt($data, $pos)) {
			$dif = $this->encodingMode->estimateBitsModeAn($pos) + 4 + $lag
				+ $this->encodingMode->estimateBitsMode8(1) // + 4 + l8
				- $this->encodingMode->estimateBitsMode8($pos + 1); // - 4 - l8
			if ($dif > 0) {
				return $this->eat8($data);
			}
		}

		$this->items = $this->encodingMode->appendNewInputItem(
			$this->items,
			Data::ENC_MODES['AN'],
			$pos,
			str_split($data)
		);
		return $pos;
	}

	protected function eatKanji(string $data): int {
		$pos = 0;
		while ($this->encodingMode->getEncodingMode($data, $pos) == Data::ENC_MODES['KJ']) {
			$pos += 2;
		}

		$this->items = $this->encodingMode->appendNewInputItem(
			$this->items,
			Data::ENC_MODES['KJ'],
			$pos,
			str_split($data)
		);
		return $pos;
	}

	protected function eat8(string $data): int {
		$lag = $this->encodingMode->getLengthIndicator(Data::ENC_MODES['AN'], $this->version);
		$lng = $this->encodingMode->getLengthIndicator(Data::ENC_MODES['NM'], $this->version);
		$pos = 1;
		$dataStrLen = strlen($data);
		while ($pos < $dataStrLen) {
			$mode = $this->encodingMode->getEncodingMode($data, $pos);
			if ($mode == Data::ENC_MODES['KJ']) {
				break;
			}

			if ($mode == Data::ENC_MODES['NM']) {
				$qix = $pos;
				while ($this->encodingMode->isDigitAt($data, $qix)) {
					++$qix;
				}

				$dif = $this->encodingMode->estimateBitsMode8($pos) // + 4 + l8
					+ $this->encodingMode->estimateBitsModeNum($qix - $pos) + 4 + $lng
					- $this->encodingMode->estimateBitsMode8($qix); // - 4 - l8
				if ($dif < 0) {
					break;
				}
				else {
					$pos = $qix;
				}
			}
			elseif ($mode == Data::ENC_MODES['AN']) {
				$qix = $pos;
				while ($this->encodingMode->isAlphanumericAt($data, $qix)) {
					++$qix;
				}

				$dif = $this->encodingMode->estimateBitsMode8($pos)  // + 4 + l8
					+ $this->encodingMode->estimateBitsModeAn($qix - $pos) + 4 + $lag
					- $this->encodingMode->estimateBitsMode8($qix); // - 4 - l8
				if ($dif < 0) {
					break;
				}
				else {
					$pos = $qix;
				}
			}
			else {
				++$pos;
			}
		}

		$this->items = $this->encodingMode->appendNewInputItem(
			$this->items,
			Data::ENC_MODES['8B'],
			$pos,
			str_split($data)
		);
		return $pos;
	}
}


//  ▀█████████▄      ▄████████     ▄████████  
//    ███    ███    ███    ███    ███    ███  
//    ███    ███    ███    ███    ███    ███  
//   ▄███▄▄▄██▀     ███    ███   ▄███▄▄▄▄██▀  
//  ▀▀███▀▀▀██▄   ▀███████████  ▀▀███▀▀▀▀▀    
//    ███    ██▄    ███    ███  ▀███████████  
//    ███    ███    ███    ███    ███    ███  
//  ▄█████████▀     ███    █▀     ███    ███

//   ▄████████   ▄██████▄   ████████▄      ▄████████  
//  ███    ███  ███    ███  ███   ▀███    ███    ███  
//  ███    █▀   ███    ███  ███    ███    ███    █▀   
//  ███         ███    ███  ███    ███   ▄███▄▄▄      
//  ███         ███    ███  ███    ███  ▀▀███▀▀▀      
//  ███    █▄   ███    ███  ███    ███    ███    █▄   
//  ███    ███  ███    ███  ███   ▄███    ███    ███  
//  ████████▀    ▀██████▀   ████████▀     ██████████

// EAN13

class EanOneThree extends Linear {
	protected const FORMAT = 'EAN13';
	protected int $code_length = 13;
	protected int $check = 0;

	protected const CHBAR = [
		'A' => [
			// left odd parity
			'0' => '0001101',
			'1' => '0011001',
			'2' => '0010011',
			'3' => '0111101',
			'4' => '0100011',
			'5' => '0110001',
			'6' => '0101111',
			'7' => '0111011',
			'8' => '0110111',
			'9' => '0001011',
		],
		'B' => [
			// left even parity
			'0' => '0100111',
			'1' => '0110011',
			'2' => '0011011',
			'3' => '0100001',
			'4' => '0011101',
			'5' => '0111001',
			'6' => '0000101',
			'7' => '0010001',
			'8' => '0001001',
			'9' => '0010111',
		],
		'C' => [
			// right
			'0' => '1110010',
			'1' => '1100110',
			'2' => '1101100',
			'3' => '1000010',
			'4' => '1011100',
			'5' => '1001110',
			'6' => '1010000',
			'7' => '1000100',
			'8' => '1001000',
			'9' => '1110100',
		],
	];

	protected const PARITIES = [
		'0' => 'AAAAAA',
		'1' => 'AABABB',
		'2' => 'AABBAB',
		'3' => 'AABBBA',
		'4' => 'ABAABB',
		'5' => 'ABBAAB',
		'6' => 'ABBBAA',
		'7' => 'ABABAB',
		'8' => 'ABABBA',
		'9' => 'ABBABA',
	];

	protected function getChecksum(string $code): int {
		$data_len = ($this->code_length - 1);
		$code_len = strlen($code);
		$sum_a = 0;
		for ($pos = 1; $pos < $data_len; $pos += 2) {
			$sum_a += (int) $code[$pos];
		}

		if ($this->code_length > 12) {
			$sum_a *= 3;
		}

		$sum_b = 0;
		for ($pos = 0; $pos < $data_len; $pos += 2) {
			$sum_b += (int) ($code[$pos]);
		}

		if ($this->code_length < 13) {
			$sum_b *= 3;
		}

		$this->check = ($sum_a + $sum_b) % 10;
		if ($this->check > 0) {
			$this->check = (10 - $this->check);
		}

		if ($code_len == $data_len) {
			// add check digit
			return $this->check;
		}

		if ($this->check !== (int) $code[$data_len]) {
			// wrong check digit
			throw new BarcodeException('Invalid check digit: ' . $this->check);
		}

		return 0;
	}

	protected function formatCode(): void {
		$code = str_pad($this->code, ($this->code_length - 1), '0', STR_PAD_LEFT);
		$this->extcode = $code . $this->getChecksum($code);
	}

	protected function setBars(): void {
		if (! is_numeric($this->code)) {
			throw new BarcodeException('Input code must be a number');
		}

		$this->formatCode();
		$seq = '101'; // left guard bar
		$half_len = (int) ceil($this->code_length / 2);
		$parity = $this::PARITIES[$this->extcode[0]];
		for ($pos = 1; $pos < $half_len; ++$pos) {
			$seq .= $this::CHBAR[$parity[($pos - 1)]][$this->extcode[$pos]];
		}

		$seq .= '01010'; // center guard bar
		for ($pos = $half_len; $pos < $this->code_length; ++$pos) {
			$seq .= $this::CHBAR['C'][$this->extcode[$pos]];
		}

		$seq .= '101'; // right guard bar
		$this->processBinarySequence($this->getRawCodeRows($seq));
	}
}

// CODE39E+

class CodeThreeNineExtCheck extends Linear {
	protected const FORMAT = 'C39E+';

	protected const CHBAR = [
		'0' => '111331311',
		'1' => '311311113',
		'2' => '113311113',
		'3' => '313311111',
		'4' => '111331113',
		'5' => '311331111',
		'6' => '113331111',
		'7' => '111311313',
		'8' => '311311311',
		'9' => '113311311',
		'A' => '311113113',
		'B' => '113113113',
		'C' => '313113111',
		'D' => '111133113',
		'E' => '311133111',
		'F' => '113133111',
		'G' => '111113313',
		'H' => '311113311',
		'I' => '113113311',
		'J' => '111133311',
		'K' => '311111133',
		'L' => '113111133',
		'M' => '313111131',
		'N' => '111131133',
		'O' => '311131131',
		'P' => '113131131',
		'Q' => '111111333',
		'R' => '311111331',
		'S' => '113111331',
		'T' => '111131331',
		'U' => '331111113',
		'V' => '133111113',
		'W' => '333111111',
		'X' => '131131113',
		'Y' => '331131111',
		'Z' => '133131111',
		'-' => '131111313',
		'.' => '331111311',
		' ' => '133111311',
		'$' => '131313111',
		'/' => '131311131',
		'+' => '131113131',
		'%' => '111313131',
		'*' => '131131311'
	];

	protected const EXTCODES = [
		'%U', '$A', '$B', '$C', '$D', '$E', '$F', '$G', '$H', '$I', '$J', '$K', '$L', '$M', '$N', '$O', '$P', '$Q', '$R', '$S', '$T', '$U', '$V', '$W', '$X', '$Y', '$Z', '%A', '%B', '%C', '%D', '%E', '', '/A', '/B', '/C', '/D', '/E', '/F', '/G', '/H', '/I', '/J', '/K', '/L', '-', '.', '/O', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '/Z', '%F', '%G', '%H', '%I', '%J', '%V', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '%K', '%L', '%M', '%N', '%O', '%W', '+A', '+B', '+C', '+D', '+E', '+F', '+G', '+H', '+I', '+J', '+K', '+L', '+M', '+N', '+O', '+P', '+Q', '+R', '+S', '+T', '+U', '+V', '+W', '+X', '+Y', '+Z', '%P', '%Q', '%R', '%S', '%T'
	];

	protected const CHKSUM = [
		'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '-', '.', '', '$', '/', '+', '%'
	];

	protected function getExtendCode(string $code): string {
		$ext = '';
		$clen = strlen($code);
		for ($chr = 0; $chr < $clen; ++$chr) {
			$item = ord($code[$chr]);
			if ($item > 127) {
				throw new BarcodeException('Invalid character: chr(' . $item . ')');
			}

			$ext .= $this::EXTCODES[$item];
		}

		return $ext;
	}

	protected function getChecksum(string $code): string {
		$sum = 0;
		$clen = strlen($code);
		for ($chr = 0; $chr < $clen; ++$chr) {
			$key = array_keys($this::CHKSUM, $code[$chr]);
			$sum += $key[0];
		}

		$idx = ($sum % 43);
		return $this::CHKSUM[$idx];
	}

	protected function formatCode(): void {
		$code = $this->getExtendCode(strtoupper($this->code));
		$this->extcode = '*' . $code . $this->getChecksum($code) . '*';
	}

	protected function setBars(): void {
		$this->ncols = 0;
		$this->nrows = 1;
		$this->bars = [];
		$this->formatCode();
		$clen = strlen($this->extcode);
		for ($chr = 0; $chr < $clen; ++$chr) {
			$char = $this->extcode[$chr];
			if (! isset($this::CHBAR[$char])) {
				throw new BarcodeException('Invalid character: chr(' . ord($char) . ')');
			}

			for ($pos = 0; $pos < 9; ++$pos) {
				$bar_width = (int) $this::CHBAR[$char][$pos];
				if ((($pos % 2) == 0) && ($bar_width > 0)) {
					$this->bars[] = [$this->ncols, 0, $bar_width, 1];
				}

				$this->ncols += $bar_width;
			}

			++$this->ncols;
		}

		--$this->ncols;
	}
}

// CODE39E

class CodeThreeNineExt extends CodeThreeNineExtCheck {
	protected const FORMAT = 'C39E';

	protected function formatCode(): void {
		$this->extcode = '*' . $this->getExtendCode(strtoupper($this->code)) . '*';
	}
}

// CODE39+

class CodeThreeNineCheck extends CodeThreeNineExtCheck {
	protected const FORMAT = 'C39+';

	protected function formatCode(): void {
		$code = strtoupper($this->code);
		$this->extcode = '*' . $code . $this->getChecksum($code) . '*';
	}
}

// QRCODE

class QrCode extends Square {
	protected const FORMAT = 'QRCODE';
	protected int $version = 0;
	protected int $level = 0;
	protected int $hint = 2;
	protected bool $case_sensitive = true;
	protected int $random_mask = -1;
	protected bool $best_mask = true;
	protected int $default_mask = 2;
	protected ByteStream $bsObj;

	protected function setParameters(): void {
		parent::setParameters();

		if (!isset($this->params[0]) || !isset(Data::ECC_LEVELS[$this->params[0]])) {
			$this->params[0] = 'L';
		}

		$this->level = Data::ECC_LEVELS[$this->params[0]];

		if (!isset($this->params[1]) || ! isset(Data::ENC_MODES[$this->params[1]])) {
			$this->params[1] = '8B';
		}

		$this->hint = Data::ENC_MODES[$this->params[1]];

		if (!isset($this->params[2]) || ($this->params[2] < 0) || ($this->params[2] > Data::QRSPEC_VERSION_MAX)) {
			$this->params[2] = 0;
		}

		$this->version = (int) $this->params[2];

		if (! isset($this->params[3])) {
			$this->params[3] = 1;
		}

		$this->case_sensitive = (bool) $this->params[3];

		if (! empty($this->params[4])) {
			$this->random_mask = (int) $this->params[4];
		}

		if (! isset($this->params[5])) {
			$this->params[5] = 1;
		}

		$this->best_mask = (bool) $this->params[5];

		if (! isset($this->params[6])) {
			$this->params[6] = 2;
		}

		$this->default_mask = (int) $this->params[6];
	}

	protected function setBars(): void {
		if (strlen((string) $this->code) == 0) {
			throw new BarcodeException('Empty input');
		}

		$this->bsObj = new ByteStream($this->hint, $this->version, $this->level);
		// generate the qrcode
		$this->processBinarySequence(
			$this->binarize(
				$this->encodeString($this->code)
			)
		);
	}

	protected function binarize(array $frame): array {
		$len = count($frame);
		foreach ($frame as &$frameLine) {
			for ($idx = 0; $idx < $len; ++$idx) {
				$frameLine[$idx] = ((ord($frameLine[$idx]) & 1) !== 0) ? '1' : '0';
			}
		}

		return $frame;
	}

	protected function encodeString(string $data): array {
		if (! $this->case_sensitive) {
			$data = $this->toUpper($data);
		}

		$split = new Split($this->bsObj, $this->hint, $this->version);
		$datacode = $this->bsObj->getByteStream($split->getSplittedString($data));
		$this->version = $this->bsObj->version;
		$encoder = new Encoder(
			$this->version,
			$this->level,
			$this->random_mask,
			$this->best_mask,
			$this->default_mask
		);
		return $encoder->encodeMask(-1, $datacode);
	}

	protected function toUpper(string $data): string {
		$len = strlen($data);
		$pos = 0;

		while ($pos < $len) {
			$mode = $this->bsObj->getEncodingMode($data, $pos);
			if ($mode == Data::ENC_MODES['KJ']) {
				$pos += 2;
			}
			else {
				if ((ord($data[$pos]) >= ord('a')) && (ord($data[$pos]) <= ord('z'))) {
					$data[$pos] = chr(ord($data[$pos]) - 32);
				}

				++$pos;
			}
		}

		return $data;
	}
}

// eof