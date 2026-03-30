<?PHP

namespace app\lib\Captcha;

/** captcha util **/

class Render {
	
	//largeur de l'image affichée (facteur) :
	private $pad_w = 4;
	//longueur de la chaine à afficher jusqu'à 10 :
	private $length = 4;
	//Police de corractère
	private $font_file = null;
	//taille de la police :
	private $font_size = 20;
	//brouillage de l'image (lignes, pixels) :
	private $badme = true;
	//sort un nombre ou une chaine alphanumérique :
	private $alphanum = false;
	//session à retourner et conserver :
	public $session = null;
	//valeur du captcha :
	private $randval = null;
	//Bordure de l'image :
	private $border = true;

	//Constructeur :
    public function __construct() {
		
		//lecture des paramètres :
		$config = Config::getInstance();
		
		//setup de la config :
		if (isset($config)) {
			foreach ($config->_vars as $prop => $value) {
				$this->$prop = $value; 
			}
		}
		
		//ecriture de la valeur aléatoire :
		$randval = $this->generate($this->length);
		
		//on retire tous les espaces et on met tout en minuscule :
		$format_randval = strtolower(str_replace(' ','',$randval));
		
		//variable de la chaine aléatoire à afficher :
		$this->randval = $format_randval;
		
		//ecriture de cette valeur en MD5 :
		$this->session =  md5($format_randval);
		
	}
	
	/** binaire de l'image **/
	public function make($image_type='png') {
		
		if(!file_exists($this->font_file))die("<h3>Captcha font file ".$this->font_file." not found !</h3>");
		
		//on envoie comme info le type d'image sur le navigateur :
		header('Content-type: image/'.$image_type);
		
		//on crée physiquement (binaire) l'image au format choisi :
		imagepng( $this->make_captcha( $this->font_file ) );
		
	}
	
	/** Constructeur de l'image **/
    private function make_captcha($font_file) {

		//corrections sur la taille de la police [min:15 max:50] :
		if($this->font_size<=14 || $this->font_size>50) $this->font_size=20;

		//taille complète en 2D de l'image générée depuis GD :
		$taille = imagettfbbox($this->font_size, 0, $font_file, $this->randval);

		//calcul de l'image depuis imagettfbbox avec sa valeur absolue abs() :
		//[0] --> Coin inférieur gauche, abscisse.
		//[1] --> Coin inférieur gauche, ordonnée.
		//[2] --> Coin inférieur droit, abscisse.
		//[3] --> Coin inférieur droit, ordonnée.
		//[4] --> Coin supérieur droit, abscisse.
		//[5] --> Coin supérieur droit, ordonnée.
		//[6] --> Coin supérieur gauche, abscisse.
		//[7] --> Coin supérieur gauche, ordonnée.
		//on ajoute la taille du padding (cadre) avec sa valeur :
		$image_width = abs($taille[4] - $taille[0]) + 10*$this->pad_w;
		$image_height = abs($taille[5] - $taille[1]) + 16; //Taille fixe.

		//création de l'image vide avec la taille calculée comme paramètre :
		$image = imagecreatetruecolor($image_width, $image_height);

		//on ajoute les couleurs :
		$text_color = imagecolorallocate($image, 100, 100, 100); //texte (ici noir)
		$back_color = imagecolorallocate($image, 255, 255, 255); //arriere plan (blanc)
		$pad_color = imagecolorallocate($image, 0, 0, 0); // bordure (noir)
		if(!$this->border)$pad_color = imagecolorallocate($image, 255, 255, 255);

		//mise en place du cadre et du fond :
		$b_1 = 2;
		$b_2 = 3;
		if(!$this->border) {
			$b_1 = 0;
			$b_2 = 0;
		}
		if($this->border)imagefilledrectangle($image, 0, 0, $image_width, $image_height, $pad_color); //Bordure
		imagefilledrectangle($image, $b_1, $b_1, $image_width-$b_2, $image_height-$b_2, $back_color); //Fond

		if($this->badme) {

		  //couleurs :
		  $line_color = imagecolorallocate($image, 100, 100, 100); //lignes (gris)
		  $pixel_color = imagecolorallocate($image, 0, 0, 255); //pixels (bleu)

		  //on ajoute des lignes aléatoires, (4) étant les nb des lignes aléatoires :
		  for ($i=0; $i<4; $i++) {
		   imageline($image, 0, rand() % $image_height, $image_width, rand() % $image_height, $line_color);
		   //largeur des lignes :
		   imagesetthickness($image, 2);
		  }
		 
		  //on ajoute les pixels, 100 étant le nombre total :
		  for ($i=0; $i<200; $i++) {
		   imagesetpixel($image, rand() % $image_width, rand() % $image_height, $pixel_color);
		  }

		}

		//on rempli l'image :
		imagefill($image, 0, 0, $pad_color);

		//coordonnées de placement du texte (au centre) :
		$x = 5*$this->pad_w;
		$y = $image_height - 8; //Taille fixe.

		//création de l'image avec son texte; equivalent à imagestring :
		//[0] --> ressource (l'image)
		//[1] --> taille de la police
		//[2] --> l'angle
		//[3] --> placement sur l'ordonnée
		//[4] --> placement sur l'abscisse
		//[5] --> couleur du texte
		//[6] --> la police
		//[7] --> la chaine (texte)
		imagettftext($image, $this->font_size, 7, $x, $y+5, $text_color, $font_file, $this->randval);

		//on renvoie le binaire brut de l'image :
		return $image;
		
		//vidage de la mémoire tampon :
		imagedestroy($image);
		
    }
	
	/** fonction pour générer une chaine aléatoire sur sa longueur (par défaut 4 chr) **/
	private function generate() {

		//Déclaration de la chaine de sortie VIDE
		//NOTE : Ceci n'est pas indispensable si l'on a activé dans le fichier php.ini ==> error_reporting=e_all & ~e_notice
		$out = (string) NULL;
		
		$ln = $this->length;

		//corrections sur le nombre choisi [min:2 max:8] :
		if($ln<=1 || $ln>8) $ln=4;

		//sort une valeur numérique par défaut :
		if(!$this->alphanum) {

			//boucle sur le nombre choisi :
			for($i=0; $i<$ln; $i++) { 
				//on mélange un chiffre entre 0 et 9 :
				$tmp = mt_rand(0, 9); 
				//on accumule les valeurs jusqu'au nombre choisi avec un espace avant :
				$out .= " ".$tmp;
			}

		} else {

			//sort une valeur alphanumérique :
			//découpage de md5 (32 chr hexa) aléatoires (par rand) par la méthode substr :
			$tmp = substr(md5(rand()), 0, $ln);

			//on recharge la valeur $tmp pour mixer les minuscules et la majuscules :
			for($i=0; $i<$ln; $i++) {
				//on mélange la chaine redécoupée :
				$tmp[$i] = ( mt_rand(0, 64) > 32 ) ? strtoupper($tmp[$i]) : strtolower($tmp[$i]);
				$out .= " ".$tmp[$i];
			}

		}
		//retour de la chaine :
		return $out." ";

	}
	
}