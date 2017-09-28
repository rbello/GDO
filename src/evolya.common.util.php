<?php

/**
 * Cette classe utilitaire propose des fonctionnalités qui ne sont pas présentes nativement
 * dans PHP.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.common.util
 */
final class PHPHelper {

	/**
	 * Renvoi un DFile pointant vers le dossier source.
	 * <br>Permet de faire référence à un fichier dans le dossier source.
	 * 
	 * @return DFile
	 */
	public static function getSourceFolder() {
		$ref = new DFile(__FILE__);
		return $ref->getParent();
	}

	/**
	 * Créer un type énuméré (enum).
	 *
	 * <br>Voici un exemple de création de l'enum CARDINAL_POINT qui représente
	 * les différents points cardinaux :
	 * <php>
	 *  PHPHelper::createEnum('CARDINAL_POINT', array(
	 *   'NORTH',
	 *   'EAST',
	 *   'SOUTH',
	 *   'WEST'
	 *  ));
	 * </php>
	 *
	 * <br>Cette méthode va créer des variables pour toutes les valeurs du type énuméré,
	 * qui peuvent être accédés de cette manière :
	 * <php>
	 *  // Get north instance
	 *  CARDINAL_POINT::$NORTH
	 *  // Get east instance
	 *  CARDINAL_POINT::$EAST
	 *  // Get south instance
	 *  CARDINAL_POINT::$SOUTH
	 *  // Get west instance
	 *  CARDINAL_POINT::$WEST
	 * </php>
	 *
	 * Pour comparer un deux enums, il y a plusieuss techniques :
	 * <php>
	 *  function test(CARDINAL_POINT $direction) {
	 *    if (CARDINAL_POINT::$SOUTH->equals($direction)) {
 	 *      // fonctionne
	 *    }
	 *    if (CARDINAL_POINT::$SOUTH == $direction) {
	 *      // fonctionne
	 *    }
	 * }
	 * </php>
	 *
	 * @param string $enumName Le nom de classe du type énuméré
	 * @param array&lt;string&gt; $values Les différentes valeurs possibles pour le type énuméré
	 * @return void
	 */
	public static function createEnum($enumName, $values) {
		# Enum egin
		$code = "final class $enumName implements Enum {\n\n";
		# Values
		foreach ($values as $v) {
			$code .= "\t/**\n";
			$code .= "\t * @var $enumName\n";
			$code .= "\t */\n";
			$code .= "\tpublic static \$$v = NULL;\n\n";
		}
		# Members variable
		$code .= "\t/**\n";
		$code .= "\t * Nom de la constante.\n";
		$code .= "\t * @var string\n";
		$code .= "\t */\n";
		$code .= "\tprivate \$name;\n\n";
		$code .= "\t/**\n";
		$code .= "\t * Valeur de la constante.\n";
		$code .= "\t * @var int\n";
		$code .= "\t */\n";
		$code .= "\tprivate \$value;\n\n";
		# Method : constructor
		$code .= "\t/**\n";
		$code .= "\t * Constructeur de l'enum $enumName.\n";
		$code .= "\t * @param string \$name Nom de la constante.\n";
		$code .= "\t * @param int \$value Valeur de la constante.\n";
		$code .= "\t */\n";
		$code .= "\tprivate function __construct(\$name, \$value) {";
		$code .= "\n\t\t\$this->name = \$name;";
		$code .= "\n\t\t\$this->value = \$value;";
		$code .= "\n\t}\n";
		# Method : getValue
		$code .= "\n\t/**\n";
		$code .= "\t * Renvoi la valeur de la constante.\n";
		$code .= "\t * @return int\n";
		$code .= "\t */";
		$code .= "\n\tpublic function getValue() {";
		$code .= "\n\t\treturn \$this->value;";
		$code .= "\n\t}\n";
		# Method : getName
		$code .= "\n\t/**\n";
		$code .= "\t * Renvoi le nom de la constante.\n";
		$code .= "\t * @return string\n";
		$code .= "\t */";
		$code .= "\n\tpublic function getName() {";
		$code .= "\n\t\treturn \$this->name;";
		$code .= "\n\t}\n";
		# Method : equals
		$code .= "\n\t/**\n";
		$code .= "\t * Teste si \$other correspond à la constante.\n";
		$code .= "\t * @param $enumName \$other La constante à comparer.\n";
		$code .= "\t * @return boolean\n";
		$code .= "\t */";
		$code .= "\n\tpublic function equals($enumName \$other) {";
		$code .= "\n\t\tif (!isset(\$other)) return FALSE;";
		$code .= "\n\t\treturn (\$other->getValue() === \$this->value);";
		$code .= "\n\t}\n";
		# Method : toString
		$code .= "\n\t/**\n";
		$code .= "\t * Affiche cet objet sous forme d'une string.\n";
		$code .= "\t * @return string\n";
		$code .= "\t */";
		$code .= "\n\tpublic function __toString() {";
		$code .= "\n\t\treturn \$this->name;";
		$code .= "\n\t}\n";
		# Method : valueOf
		$code .= "\n\t/**\n";
		$code .= "\t * Renvoi la constante dont le nom est \$name, ou NULL si aucune\n";
		$code .= "\t * constante ne porte ce nom.\n";
		$code .= "\t * @param string \$name Le nom à comparer.\n";
		$code .= "\t * @return $enumName\n";
		$code .= "\t */";
		$code .= "\n\tpublic static function valueOf(\$name) {";
		$code .= "\n\t\tif (!isset(\$name)) return NULL;";
		foreach ($values as $k => $v) {
			$code .= "\n\t\tif (\$name == '$v') return self::\$$v;";
		}
		$code .= "\n\t\treturn NULL;";
		$code .= "\n\t}\n";
		# Method : init
		$code .= "\n\t/**\n";
		$code .= "\t * Initialise les constantes de l'enum.\n";
		$code .= "\t * @return void\n";
		$code .= "\t */";
		$code .= "\n\tpublic static function init() {";
		$i = 0;
		foreach ($values as $v) {
			$code .= "\n\t\tself::\$$v = new $enumName('$v', $i);";
			$i++;
		}
		$code .= "\n\t}";
		# Enum end
		$code .= "\n}\n";
		# Init enum
		$code .= "\n$enumName::init();";
		echo "<pre>$code</pre>";
		eval($code);
	}

	/**
	 * Verifie un argment et lève des exceptions en cas de problème.
	 *
	 * <br>Exemple d'utilisation :
	 * <php>
	 *  class A {
	 *    public function test($param) {
	 *      $param = @PHPHelper::checkArgument('$param', $param, 'string|int');
	 *      // L'argument $param existe, n'est pas NULL, est de type string ou integer
	 *    }
	 *  }
	 * </php>
	 *
	 * La méthode retourne la variable $value. Si la variable $value n'existe pas
	 * et que $notNull vaut TRUE, la méthode retourne NULL.
	 *
	 * @param string $name Le nom de l'argument, avec le signe dollar ($).
	 * @param mixed $value La variable à tester.
	 * @param string $type Les différents types ou classes qui sont acceptés.
	 * @param boolean $notNull Indique si la variable peut valoir NULL ou non.
	 * @param boolean $cast Indique si les valeurs numériques doivent être castés.
	 * @return mixed
	 * @throws MissingArgumentException
	 * @throws BadArgumentTypeException
	 */
	public static function checkArgument($name, $value, $type, $notNull=TRUE, $cast=FALSE) {

		if (!isset($value)) {
			if (!$notNull) {
				return NULL;
			}
			throw new MissingArgumentException($name, $type);
		}
		$types = explode('|', $type);
		foreach ($types as $t) {
			switch ($t) {
				case 'string' :
					if (is_string($value)) return $value;
					break;
				case 'bool' : case 'boolean' :
					if (is_bool($value)) return $value;
					break;
				case 'int' : case 'integer' : case 'long' :
					if (is_int($value)) return $value;
					if (is_long($value)) return $value;
					if ($cast) {
						if (is_float($value)) return (int) $value;
						if (is_double($value)) return (int) $value;
					}
					break;
				case 'float' : case 'double' :
					if (is_float($value)) return $value;
					if (is_double($value)) return $value;
					if ($cast) {
						if (is_int($value)) return (float) $value;
						if (is_long($value)) return (float) $value;
					}
					break;
				case 'number' :
					if (is_int($value)) return $value;
					if (is_float($value)) return $value;
					break;
				default :
					if ($value instanceof $t) {
						return $value;
					}
					break;
			}
		}
		throw new BadArgumentTypeException($name, $value, $type);
	}

}

/**
 * Objet qui enregistre toutes les données envoyées au navigateur du client.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.common.util
 */
class DOutputBuffer {

	/**
	 * Indicateur d'enregistrement.
	 * @var boolean
	 */
	protected $recording = FALSE;

	/**
	 * Contient les données enregistrées par le buffer.
	 * @var boolean
	 */
	protected $contents = NULL;

	/**
	 * Constructeur de la classe DOutputBuffer.
	 * Active le buffer. Toutes les données envoyées au navigateur du client
	 * seront capturées par cet objet.
	 * 
	 * @construct DOutputBuffer()
	 *  Construit un DOutputBuffer.
	 */
	public function __construct() {
		$this->start();
	}

	/**
	 * Indique si le buffer est actuellement en cours d'enregistrement.
	 * 
	 * @return boolean
	 */
	public function isRecording() {
		return $this->recording;
	}

	/**
	 * Active le buffer de sortie.
	 * 
	 * @return void
	 */
	protected function start() {
		$this->recording = @ob_start();
	}

	/**
	 * Renvoi les données capturées par le buffer.
	 * 
	 * @return string
	 */
	public function getContents() {
		return $this->contents;
	}

	/**
	 * Indique si le buffer est vide.
	 * 
	 * @return boolean
	 */
	public function isEmpty() {
		return empty($this->contents);
	}

	/**
	 * Stop l'enregistrement du buffer.
	 * 
	 * @return void
	 */
	public function stop() {
		if (!$this->recording) return;
		$this->contents = @ob_get_contents();
		if (@!ob_end_clean()) {
		}
		$this->recording = FALSE;
	}

	/**
	 * Suppression de cet objet.
	 * Restaure le niveau d'erreur.
	 * 
	 * @return void
	 */
	public function dispose() {
		if ($this->recording) {
			$this->stop();
		}
		$this->contents = NULL;
	}

	/**
	 * Implémentation de la méthode magique pour supprimer cet objet.
	 * 
	 * @param string $name Nom de la variable.
	 * @return void
	 */
	public function __unset($name) {
		$this->dispose();
	}

	/**
	 * Désactive ce comportement.
	 * 
	 * @return void
	 * @throws UnsupportedOperationException Toujours.
	 */
	public function __clone() {
		throw new UnsupportedOperationException();
	}

}

/**
 * Objet qui enregistre les erreurs, warning et notices qui peuvent être provoquées
 * par les fonctions PHP. Le constructeur change le niveau d'error reporting au plus
 * haut niveau (<code>E_ALL</code>) afin d'enregistrer un maximum d'erreurs. Lorsque l'objet est
 * détruit, le niveau d'error reporting retourne au niveau initial.
 * <br>Le DErrorRecorder utilise les fonctions de buffer de sortie (output buffer) ce
 * qui veut dire que tous le contenu envoyés au navigateur du client sera capturé
 * par l'objet, tant que celui-ci sera activé.
 * 
 * TODO Ce serait sympa si le buffer pouvait séparer et compter les erreurs/warning/notices.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.common.util
 */
class DErrorRecorder extends DOutputBuffer {

	/**
	 * Le niveau d'erreur avant que l'DErrorRecorder ne le modifie.
	 * @var unknown_type
	 */
	protected $errorLevel;

	/**
	 * Constructeur de la classe DErrorRecorder. L'objet s'active et
	 * va enregistrer toutes les erreurs qui sont en dessous du niveau $level.
	 * 
	 * @construct DErrorRecorder()
	 *  Construit un DErrorRecorder avec le niveau d'erreur par défaut (E_ALL).
	 * @construct DErrorRecorder(int $level)
	 *  Construit un DErrorRecorder avec le niveau d'erreur $level.
	 * 
	 * @param int $level Niveau d'erreur.
	 */
	public function __construct($level=E_ALL) {

		$this->errorLevel = error_reporting();
		error_reporting($level);

		parent::__construct();

		// Si la bufferisation ne veut pas se lancer, on restaure le niveau
		// d'erreur reporting
		if (!$this->recording) {
			error_reporting($this->errorLevel);
		}

	}

	/**
	 * Renvoi le niveau d'erreur avant que l'DErrorRecorder ne le modifie.
	 * 
	 * @return int
	 */
	public function getErrorLevel() {
		return $this->errorLevel;
	}

	/**
	 * Stop l'enregistrement, et restaure le niveau d'erreur.
	 * 
	 * @return void
	 */
	public function stop() {
		parent::stop();
		error_reporting($this->errorLevel);
	}

	/**
	 * Suppression de cet objet.
	 * Restaure le niveau d'erreur.
	 * 
	 * @return void
	 */
	public function dispose() {
		parent::dispose();
		error_reporting($this->errorLevel);
	}

	/**
	 * Implémentation de la méthode magique pour supprimer cet objet.
	 * 
	 * @param string $name Nom de la variable.
	 * @return void
	 */
	public function __unset($name) {
		$this->dispose();
	}

	/**
	 * Désactive ce comportement.
	 * 
	 * @return void
	 * @throws UnsupportedOperationException Toujours.
	 */
	public function __clone() {
		throw new UnsupportedOperationException();
	}

}

?>