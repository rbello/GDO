<?php

/**
 * Classe de base pour toutes les exceptions propres à GDO.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.exceptions
 */
class GDOException extends Exception {
}

/**
 * Exception levée lorsqu'un filtre non supporté essaye de s'appliquer.
 * <br>Un filtre non supporté désigne une version de PHP ou de GD plus ancienne.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.exceptions
 */
class UnsupportedFilterException extends UnsupportedOperationException {
}

/**
 * Exception levée lorsque des dimensions passées en paramètre sont invalide.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.exceptions
 */
class InvalidDimensionException extends IllegalArgumentException {
}

/**
 * Exception levée lorsqu'une largeur passées en paramètre est invalide.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.exceptions
 */
class InvalidWidthException extends InvalidDimensionException {
}

/**
 * Exception levée lorsqu'une hauteur passées en paramètre est invalide.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.exceptions
 */
class InvalidHeightException extends InvalidDimensionException {
}

/**
 * Exception lorsque la création d'une image est impossible. 
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.exceptions
 */
class ImageCreateException extends GDOException {
}

/**
 * Exception levée lorsqu'on tente de modifiers les headers alors que celles-ci ont
 * déja été envoyées.
 * <br>Cette exception est levée si du code HTML a déja été envoyé au navigateur du client
 * (sortie standard) alors qu'une image tente d'être envoyée au navigateur.
 * <br>Cette exception peut aussi correspondre à un problème d'encodage UTF-8 : pour vous
 * assurer qu'aucun caractère parasite n'a été produit par l'encodage UTF-8, assurez vous
 * de choisir un encodage UTF-8 sans BOM.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.exceptions
 */
class HeadersSentException extends Exception {
}

/**
 * Exception levée lorsqu'on tente d'effectuer une opération sur une resource non-liée.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.exceptions
 */
class ImageNotBoundException extends GDOException {
}

/**
 * Exception levée lorsque le fichier passé en argument est introuvable ou de type invalide.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.exceptions
 */
class FileNotFoundException extends GDOException {

	/**
	 * Constructeur de la classe FileNotFoundException.
	 * 
	 * @construct FileNotFoundException(string $file)
	 *  Construit une exception désignant le chemin $file.
	 * @construct FileNotFoundException(DFile $file)
	 *  Construit une exception désignant le fichier $file.
	 * 
	 * @param string|DFile $file
	 */
	public function __construct($file) {
		if ($file instanceof DFile) {
			$file = $file->getAbsolutePath();
		}
		parent::__construct("File not found : $file");
	}

}

/**
 * Exception levée lorsque le type de police n'est pas supporté.
 * <br>Théoriquement, cette méthode n'est pas sensée se produire car les méthodes
 * doivent vérifier le type de police avant de l'accepter. Néanmoins, si cette exception
 * est provoquée, vérifiez que le type de police est bien supporté par GDO.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.exceptions
 */
class UnknownFontTypeException extends GDOException {
}

/**
 * Exception levée lorsqu'une tentative de modification est effectuée sur un objet
 * finalisé. Un objet finalisé n'est plus modifiable.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.exceptions
 */
class FinalizedObjectException extends GDOException {
}

/**
 * Exception levée lorsque les données à traiter sont invalides. Suivant les situations, cette
 * exception peut désigner un format de fichier invalide, une source de donnée corrompue,
 * une erreur de récupération de données...
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.exceptions
 */
class InvalidDataException extends GDOException {
}

/**
 * Exception levée lorsque la mémoire disponible ne permet pas de réaliser une opération. Pour
 * plus de détails, voyez la documentation sur le DMemoryHelper.
 *
 * @author evolya.free.fr
 * @copyright Copyright (c) evolya.free.fr
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0
 * @package evolya.gdo.exceptions
 */
class MemoryException extends GDOException {
}

?>