
<div class="box-accueil">
	
	<h2>Bonjour, <?php echo $info['user']['firstname'] ?></h2>

	<hr>

	<div class="wrap">
	
	<p>
	
	<img class="img-dyb" src="<?php echo $info['user']['avatars']['tiny'] ?>" alt="Votre Photo"/> 

	Si vous voyez votre photo c'est que votre, CV est correctement connecté.</p>

	<p class="clearfix">

	Votre derniere mise à jour date du : 

	<strong>

	<?php echo date('d/m/y H:i',filemtime ($url_folder.'/'.$id.'_cv.xml' )) ?>

	</strong>

	<div class="center">

		<a  href="<?php admin_url() ?>admin.php?page=dyb-maj">Mettre vos données à jour ! </a>

	</div>

	</p>

	<hr>

	<h3>Les Shortcodes</h3>

	<ul>
		
		<li>
			<code>[dyb-skill][/dyb-skill]</code> 
			<p>Permet d'afficher vos compétences</p>
		</li>

		<li>
			<code>[dyb-exp][/dyb-exp]</code> 
			<p>Permet d'afficher votre experience</p>

		</li>
		
		<li>
			<code>[dyb-school][/dyb-school]</code> 
			<p>Affiche votre scolarité</p>
		</li>
	
	</ul>

	<h3>Tu sais coder !</h3>
	
	<p> Si tu n'as pas peur du code, viens <a href="http://labo.saugrin-sonia.fr/wp-dyb" target="_blank">içi</a>. Il y à plus de fonctions.</p>
	
	<hr>
	
	<h3>Soit sympa ! Soutiens Moi...

	<div class="center">
	
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
		
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="Z3DEBHJW8D8UU">
			<input type="image" src="https://www.paypalobjects.com/fr_XC/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
			<img alt="" border="0" src="https://www.paypalobjects.com/fr_XC/i/scr/pixel.gif" width="1" height="1">
	
		</form>
	
	</div>

  	</div>

</div>
