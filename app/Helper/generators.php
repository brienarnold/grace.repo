<?php
	/**
	 * Created by PhpStorm.
	 * User: Phillip Madsen
	 * Date: 7/11/2016
	 * Time: 3:46 PM
	 */



	function generateSku(){
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$sku      = mt_rand(10000, 99999) . mt_rand(10000, 99999) . $characters[mt_rand(0, strlen($characters) - 1)];
		return str_shuffle($sku);
	}

	function generatePin(){
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$pin      = mt_rand(10000, 99999) . mt_rand(10000, 99999) . $characters[mt_rand(0, strlen($characters) - 1)];
		return str_shuffle($pin);
	}


	function generateCoupon(){
		$chars  = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$coupon = "";
		for ($i = 0; $i < 6; $i++) {
			$coupon .= $chars[mt_rand(0, strlen($chars) - 1)];
		}
	}








function generateModel()
{

	$num_of_ids = 10000; //Number of "ids" to generate.
	$i = 0; //Loop counter.
	$n = 0; //"id" number piece.
	$l = "AAA"; //"id" letter piece.

	while ($i <= $num_of_ids) {
	    $id = $l . sprintf("%04d", $n); //Create "id". Sprintf pads the number to make it 4 digits.
	    echo $id . "<br>"; //Print out the id.

	    if ($n == 9999) { //Once the number reaches 9999, increase the letter by one and reset number to 0.
	        $n = 0;
	        $l++;
	    }

	    $i++; $n++; //Letters can be incremented the same as numbers. Adding 1 to "AAA" prints out "AAB".
	}
}
// The previous code would print out the following, AAA0000 AAA0001 AAA0002 AAA0003 AAA0004 AAA0005 AAA0006 AAA0007













// class upca extends BarCode {
// 	protected $keys = array(), $code = array(), $codeParity = array();
// 	protected $text;
// 	protected $textfont;

// 	/**
// 	 * Constructor
// 	 *
// 	 * @param int $maxHeight
// 	 * @param FColor $color1
// 	 * @param FColor $color2
// 	 * @param int $res
// 	 * @param string $text
// 	 * @param int $textfont
// 	 */
// 	public function __construct($maxHeight,FColor $color1,FColor $color2,$res,$text,$textfont) {
// 		BarCode::__construct($maxHeight,$color1,$color2,$res);
// 		$this->keys = array('0','1','2','3','4','5','6','7','8','9');
// 		// Left-Hand Odd Parity starting with a space
// 		// Left-Hand Even Parity is the inverse (0=0012) starting with a space
// 		// Right-Hand is the same of Left-Hand starting with a bar
// 		$this->code = array(
// 			'2100',	/* 0 */
// 			'1110',	/* 1 */
// 			'1011',	/* 2 */
// 			'0300',	/* 3 */
// 			'0021',	/* 4 */
// 			'0120',	/* 5 */
// 			'0003',	/* 6 */
// 			'0201',	/* 7 */
// 			'0102',	/* 8 */
// 			'2001'	/* 9 */
// 		);
// 		// Parity, 0=Odd, 1=Even for manufacturer code. Depending on 1st System Digit
// 		$this->codeParity = array(
// 			array(0,0,0,0,0),	/* 0 */
// 			array(0,1,0,1,1),	/* 1 */
// 			array(0,1,1,0,1),	/* 2 */
// 			array(0,1,1,1,0),	/* 3 */
// 			array(1,0,0,1,1),	/* 4 */
// 			array(1,1,0,0,1),	/* 5 */
// 			array(1,1,1,0,0),	/* 6 */
// 			array(1,0,1,0,1),	/* 7 */
// 			array(1,0,1,1,0),	/* 8 */
// 			array(1,1,0,1,0)	/* 9 */
// 		);
// 		$this->setText($text);
// 		$this->textfont = $textfont;
// 	}

// 	/**
// 	 * Saves Text
// 	 *
// 	 * @param string $text
// 	 */
// 	public function setText($text) {
// 		$this->text = $text;
// 	}

// 	private function inverse($text,$inverse=1) {
// 		if($inverse == 1)
// 			$text = strrev($text);
// 		return $text;
// 	}

// 	/**
// 	 * Draws the barcode
// 	 *
// 	 * @param ressource $im
// 	 */
// 	public function draw($im) {
// 		$error_stop = false;

// 		// Checking if all chars are allowed
// 		for($i=0;$i<strlen($this->text);$i++) {
// 			if(!is_int(array_search($this->text[$i],$this->keys))) {
// 				$this->DrawError($im,'Char \''.$this->text[$i].'\' not allowed.');
// 				$error_stop = true;
// 			}
// 		}
// 		if($error_stop == false) {
// 			// Must contains 11 chars
// 			if(strlen($this->text) != 11) {
// 				$this->DrawError($im,'Must contains 11 chars, the 12th digit is automatically added.');
// 				$error_stop = true;
// 			}
// 			if($error_stop == false) {
// 				// The following code is exactly the same as EAN13. We just add a 0 in front of the code !
// 				$this->text = '0'.$this->text;

// 				// Calculating Checksum
// 				// Consider the right-most digit of the message to be in an "odd" position,
// 				// and assign odd/even to each character moving from right to left
// 				// Odd Position = 3, Even Position = 1
// 				// Multiply it by the number
// 				// Add all of that and do 10-(?mod10)
// 				$odd = true;
// 				$checksum=0;
// 				for($i=strlen($this->text);$i>0;$i--) {
// 					if($odd==true) {
// 						$multiplier=3;
// 						$odd=false;
// 					}
// 					else {
// 						$multiplier=1;
// 						$odd=true;
// 					}
// 					$checksum += $this->keys[$this->text[$i - 1]] * $multiplier;
// 				}
// 				$checksum = 10 - $checksum % 10;
// 				$checksum = ($checksum == 10)?0:$checksum;
// 				$this->text .= $this->keys[$checksum];
// 				// If we have to write text, we move the barcode to the right to have space to put system digit
// 				$this->positionX = ($this->textfont == 0)?0:10;
// 				// Starting Code
// 				$this->DrawChar($im,'000',1);
// 				// Draw Second Code
// 				$this->DrawChar($im,$this->findCode($this->text[1]),2);
// 				// Draw Manufacturer Code
// 				for($i=0;$i<5;$i++)
// 					$this->DrawChar($im,$this->inverse($this->findCode($this->text[$i + 2]),$this->codeParity[$this->text[0]][$i]),2);
// 				// Draw Center Guard Bar
// 				$this->DrawChar($im,'00000',2);
// 				// Draw Product Code
// 				for($i=7;$i<13;$i++)
// 					$this->DrawChar($im,$this->findCode($this->text[$i]),1);
// 				// Draw Right Guard Bar
// 				$this->DrawChar($im,'000',1);
// 				$this->lastX = $this->positionX;
// 				$this->lastY = $this->maxHeight;
// 				$this->DrawText($im);
// 			}
// 		}
// 	}

// 	/**
// 	 * Overloaded method for drawing special label
// 	 *
// 	 * @param ressource $im
// 	 */
// 	protected function DrawText($im) {
// 		if($this->textfont != 0) {
// 			$bar_color = (is_null($this->color1))?NULL:$this->color1->allocate($im);
// 			if(!is_null($bar_color)) {
// 				$rememberX = $this->positionX;
// 				$rememberH = $this->maxHeight;
// 				// We increase the bars

// 				// First 2 Bars
// 				$this->maxHeight = $this->maxHeight + 9;
// 				$this->positionX = 10;
// 				$this->DrawSingleBar($im,$this->color1);
// 				$this->positionX += $this->res*2;
// 				$this->DrawSingleBar($im,$this->color1);

// 				// Attemping to increase the 2 following bars
// 				$this->positionX += $this->res;
// 				$temp_value = $this->findCode($this->text[1]);
// 				$this->DrawChar($im,$temp_value,2);

// 				// Center Guard Bar
// 				$this->positionX += $this->res*38;
// 				$this->DrawSingleBar($im,$this->color1);
// 				$this->positionX += $this->res*2;
// 				$this->DrawSingleBar($im,$this->color1);

// 				// Attemping to increase the 2 last bars
// 				$this->positionX += $this->res*35;
// 				$temp_value = $this->findCode($this->text[12]);
// 				$this->DrawChar($im,$temp_value,1);

// 				// Completly last bars
// 				$this->DrawSingleBar($im,$this->color1);
// 				$this->positionX += $this->res*2;
// 				$this->DrawSingleBar($im,$this->color1);


// 				$this->positionX = $rememberX;
// 				$this->maxHeight = $rememberH;
// 				imagechar($im,$this->textfont,1,$this->maxHeight-(imagefontheight($this->textfont)/2),$this->text[1],$bar_color);
// 				imagestring($im,$this->textfont,10+(10*$this->res+48*$this->res)/2-imagefontwidth($this->textfont)*(5/2),$this->maxHeight+1,substr($this->text,2,5),$bar_color);
// 				imagestring($im,$this->textfont,10+46*$this->res+(3*$this->res+42*$this->res)/2-imagefontwidth($this->textfont)*(5/2),$this->maxHeight+1,substr($this->text,7,5),$bar_color);
// 				imagechar($im,$this->textfont,1+10+(46+48+3)*$this->res,$this->maxHeight-(imagefontheight($this->textfont)/2),$this->text[12],$bar_color);
// 			}
// 			$this->lastY = $this->maxHeight + imagefontheight($this->textfont);
// 			$this->lastX += 2+imagefontwidth($this->textfont);
// 		}
// 	}
// };















	// public static function getAvatar($id){
	// 	$user = \App\User::find($id);
	// 	$profile = $user->Profile;
	// 	$name = ($user->name) ? : $user->username;
	// 	foreach($user->roles as $role)
	// 		$role_name = $role->display_name;
	// 	$tooltip = ucwords($name).' (Role: '.$role_name.')';
	// 	$tooltip .= (!Entrust::hasRole('user')) ? ' Email: '. $user->email : '';

	// 	if(isset($profile->photo))
	// 		echo '<img src="/assets/user/'.DOMAIN.'/'.$profile->photo.'" class="media-object img-circle" style="width:57px;" alt="User avatar" data-toggle="tooltip" title="'.$tooltip.'">';
	// 	else
	// 		echo '<div class="textAvatar" data-toggle="tooltip" title="'.$tooltip.'">'.$name.'</div>';
	// }


