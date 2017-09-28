<?php

if (!class_exists('UnsupportedOperationException')) {

	/**
	 * Exception levée lorsqu'une opération n'est pas supportée.
	 *
	 * @author evolya.free.fr
	 * @copyright Copyright (c) evolya.free.fr
	 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
	 * @package evolya.common.exceptions
	 */
	class UnsupportedOperationException extends Exception {
	}

}

if (!class_exists('OutOfBoundsException')) {

	/**
	 * Exception levée lorsque les limites sont dépassés.
	 *
	 * @author evolya.free.fr
	 * @copyright Copyright (c) evolya.free.fr
	 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
	 * @package evolya.common.exceptions
	 */
	class OutOfBoundsException extends Exception {
	}

}

if (!class_exists('IllegalArgumentException')) {

	/**
	 * Exception correspondante à un paramètre invalide, passé en argument d'une méthode.
	 *
	 * @author evolya.free.fr
	 * @copyright Copyright (c) evolya.free.fr
	 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
	 * @package evolya.common.exceptions
	 */
	class IllegalArgumentException extends Exception {
	}

}

if (!class_exists('MissingArgumentException')) {

	/**
	 * Exception correspondante à un paramètre manquant, passé en argument d'une méthode.
	 *
	 * @author evolya.free.fr
	 * @copyright Copyright (c) evolya.free.fr
	 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
	 * @package evolya.common.exceptions
	 */
	class MissingArgumentException extends IllegalArgumentException {

		/**
		 * Constructeur de la classe MissingArgumentException.
		 * 
		 * @param string $argName Nom de la variable manquante.
		 * @param string $argType Type(s) de la variable manquante.
		 */
		public function __construct($argName, $argType) {
			parent::__construct("Missing argument $argName ($argType)");
		}

	}

}

if (!class_exists('BadArgumentTypeException')) {

	/**
	 * Exception correspondante à un paramètre d'un type invalide, passé en argument d'une méthode.
	 *
	 * @author evolya.free.fr
	 * @copyright Copyright (c) evolya.free.fr
	 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
	 * @package evolya.common.exceptions
	 */
	class BadArgumentTypeException extends IllegalArgumentException {

		/**
		 * Constructeur de la classe BadArgumentTypeException.
		 * 
		 * @param string $argName Nom de la variable.
		 * @param mixed $argValue Valeur de la variable.
		 * @param string $argType Type(s) de la variable.
		 */
		public function __construct($argName, $argValue, $argType) {
			$type = gettype($argValue);
			if ($type == 'object') $type = get_class($argValue);
			parent::__construct("Bad argument type passed to $argName; must be $argType, instance of $type given");
		}

	}

}

?>