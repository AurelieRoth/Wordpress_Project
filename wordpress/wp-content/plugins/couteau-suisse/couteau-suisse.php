<?php
/*
Plugin Name: Couteau Suisse
Description: Comme un couteau suisse, ce plugin contient des fonctionnalités diverses et variées, s'adaptant à l'architecture du site.
Version: 0.0.3
Author: DaaTeam
*/
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

function check_create_database() {
	maybe_create_table("utilisateur", "CREATE TABLE utilisateur (id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, nom VARCHAR(255), mail VARCHAR(255))");
	maybe_create_table("questionnaire", "CREATE TABLE questionnaire (id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, titre VARCHAR(255), auteur VARCHAR(255), creation DATETIME)");
	maybe_create_table("question", "CREATE TABLE question (id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, idfrom INT NOT NULL, contenu VARCHAR(255))");
	maybe_create_table("reponses", "CREATE TABLE reponses (contenu VARCHAR(255), ip VARCHAR(255), agent VARCHAR(255), envoi DATETIME)");
}
register_activation_hook(__FILE__,"check_create_database");

function check_delete_database() {
	global $wpdb;
	$wpdb->query("DROP TABLE IF EXISTS utilisateur");
	$wpdb->query("DROP TABLE IF EXISTS questionnaire");
	$wpdb->query("DROP TABLE IF EXISTS question");
	$wpdb->query("DROP TABLE IF EXISTS reponses");
}
register_deactivation_hook(__FILE__, "check_delete_database");

function exec_couteau_suisse() {
	manage_form();
	generate_quizzmenu();
	generate_usermenu();
}
function generate_quizzmenu() {
	$name = str_replace(".php", "-",__FILE__);
	add_menu_page("Questions", "Questions (CS)", "administrator", $name."questions", "content_questionnairelist_menu_page");
	add_submenu_page($name."questions", "Questionnaires", "Liste des questionnaires", "administrator", $name."questions", "content_questionnairelist_menu_page");
	add_submenu_page($name."questions", "Créer un Questionnaire", "Création", "administrator", $name."questions-create", "content_createquestionnaire_menu_page");
	add_submenu_page($name."questions", "Supprimer un Questionnaire", "Supression", "administrator", $name."questions-delete", "content_deletequestionnaires_menu_page");
	add_submenu_page($name."questions", "Ajouter une Question", "Ajout", "administrator", $name."questions-add", "content_addquestion_menu_page");
	add_submenu_page($name."questions", "Modifier un Questionnaire", "Modification", "administrator", $name."questions-update", "content_updatequestionnaire_menu_page");
}
function generate_usermenu() {
	$name = str_replace(".php", "-",__FILE__);
	add_menu_page("Utilisateurs", "Utilisateurs (CS)", "administrator", $name."users", "content_userslist_menu_page");
	add_submenu_page($name."users", "Utilisateurs", "Liste des utilisateurs", "administrator", $name."users", "content_userslist_menu_page");
	add_submenu_page($name."users", "Créer un Utilisateur", "Création", "administrator", $name."user-create", "content_createuser_menu_page");
	add_submenu_page($name."users", "Modifier un Utilisateur", "Modification", "administrator", $name."user-update", "content_updateuser_menu_page");
	add_submenu_page($name."users", "Supprimer un Utilisateur", "Supression", "administrator", $name."user-delete", "content_deleteuser_menu_page");
}
function manage_form() {
	global $wpdb;
	if (!empty($_POST)) {
		//# Vérifier les permissions d'administrations #//
		if (current_user_can('manage_options')) {
			//# Créer un utilisateur #//
			if (!empty($_POST["create_users"]) && isset($_POST["nom"], $_POST["mail"])) {
				$_POST["nom"] = trim($_POST["nom"]);
				$_POST["mail"] = trim($_POST["mail"]);
				if ($_POST["nom"] === "" || $_POST["mail"] === "") {
					wp_die("Champs incomplets : Impossible de créer l'utilisateur.", "Erreur");
				}
				$array = ["nom" => $_POST["nom"], "mail" => $_POST["mail"]];
				if (empty($wpdb->get_results("SELECT * FROM utilisateur WHERE mail = '".$_POST["mail"]."'"))) {
					if ($wpdb->insert("utilisateur", $array)) {
						wp_die("Utilisateur créé avec succès ! <a href='/wordpress/wordpress/wp-admin/'>Retour</a>", "Succès !");
					}
					else {
						wp_die("L'utilisateur n'a pas pu être créé pour une raison inconnue. <a href='/wordpress/wordpress/wp-admin/'>Retour</a>", "Erreur Inconnue");
					}
				}
				else {
					wp_die("L'utilisateur n'a pas pu être créé car le mail est déjà utilisé pour un autre utilisateur. <a href='/wordpress/wordpress/wp-admin/'>Retour</a>", "Echec : Mail reconnu");
				}
			}
			//# Supprimer un utilisateur #//
			if (!empty($_POST["delete_users"]) && isset($_POST["mail"])) {
				$mail = trim($_POST["mail"]);
				if ($wpdb->delete("utilisateur", ["mail" => $_POST["mail"]])) {
					wp_die("L'utilisateur a été supprimé avec succès ! <a href='/wordpress/wordpress/wp-admin/'>Retour</a>", "Supression réussie");
				}
				else {
					wp_die("L'utilisateur n'a pas été supprimé pour une raison inconnue. <a href='/wordpress/wordpress/wp-admin/'>Retour</a>", "Erreur Inconnue");
				}
			}
			//# Modifier un utilisateur #//
			if (!empty($_POST["update_users"] && isset($_POST["nom"]) && isset($_POST["mail"]))) {
				$mail = trim($_POST["mail"]);
				$nom = trim($_POST["nom"]);
				if ($wpdb->update("utilisateur", ["nom" => $_POST["nom"]], ["mail" => $_POST["mail"]])) {
					wp_die("L'utilisateur a bien été modifié ! <a href='/wordpress/wordpress/wp-admin/'>Retour</a>", "Modification réussie");
				}
				else {
					wp_die("L'utilisateur n'existe pas.  <a href='/wordpress/wordpress/wp-admin/'>Retour</a>", "Echec de la modification");
				}
			}
			//# Ajouter une question #//
			if (!empty($_POST["add_question"]) && isset($_POST["questionnaire"], $_POST["question"])) {
				$question = htmlspecialchars(trim($_POST["question"]));
				$questionnaire = htmlspecialchars(trim($_POST["questionnaire"]));
				if ($question != "" && $questionnaire != "") {
					$id = $wpdb->get_results("SELECT * FROM questionnaire WHERE titre = '".$questionnaire."'")[0]->id;
					$question_info = [
						"idfrom" => intval($id),
						"contenu" => $question
					];
					if ($wpdb->insert("question", $question_info)) {
						wp_die("Votre question '$question' a bien été ajouté au questionnaire '$questionnaire' ! <a href='/wordpress/wordpress/wp-admin/'>Retour</a>", "Succès");
					}
					else {
						wp_die("Votre question n'a pas pu être ajouté au questionnaire '$questionnaire' ! <a href='/wordpress/wordpress/wp-admin/'>Retour</a>");
					}
				}
			}
			//# Modifier un questionnaire (Modifier le titre) #//
			if (!empty($_POST["update_question"]) && isset($_POST["oldquestionnaire"], $_POST["newquestionnaire"])) {
				$oldq = htmlspecialchars(trim($_POST["oldquestionnaire"]));
				$newq = htmlspecialchars(trim($_POST["newquestionnaire"]));
				if ($oldq != "" && $newq != "") {
					if (empty($wpdb->get_results("SELECT * FROM questionnaire WHERE titre = '".$newq."'"))) {
						if ($wpdb->update("questionnaire", ["titre"=> $newq], ["titre"=>$oldq])) {
							wp_die("Le questionnaire a bien été modifié ! <a href='/wordpress/wordpress/wp-admin/'>Retour</a>", "Modification réussie");
						}
						else {
							wp_die("Le questionnaire n'existe pas.  <a href='/wordpress/wordpress/wp-admin/'>Retour</a>", "Echec de la modification");
						}
					}
					else {
						wp_die("Un autre questionnaire porte déjà ce nom. <a href='/wordpress/wordpress/wp-admin/'>Retour</a>");
					}
				}
			}
			//# Suprimmer un questionnaire #//
			if (!empty($_POST["delete_question"]) && isset($_POST["questionnaire"])) {
				$questionnaire = trim(htmlspecialchars($_POST["questionnaire"]));
				$id = $wpdb->get_results("SELECT * FROM questionnaire WHERE titre = '".$questionnaire."'")[0]->id;
				if ($wpdb->delete("question", ["idfrom" => $id]) && $wpdb->delete("questionnaire", ["titre"=> $questionnaire])) {
					wp_die("Le questionnaire a été supprimé avec succès ! <a href='/wordpress/wordpress/wp-admin/'>Retour</a>", "Supression réussie");
				}
				else {
					wp_die("Le questionnaire n'a pas pu être supprimé pour une raison inconnue. <a href='/wordpress/wordpress/wp-admin/'>Retour</a>", "Erreur Inconnue");
				}
			}
			//# Créer un questionnaire #//
			if (!empty($_POST["create_question"]) && isset($_POST["questionnaire"], $_POST["question"])) {
				$questionnaire = htmlspecialchars(trim($_POST["questionnaire"]));
				$question = htmlspecialchars(trim($_POST["question"]));
				if ($question !== "" && $questionnaire !== "") {
					$obj = getdate();
					$questionnaire_info = [
						"titre" => $questionnaire, 
						"auteur" => wp_get_current_user()->user_login,
						"creation" => date('Y-m-d H:i:s')
					];
					if (empty($wpdb->get_results("SELECT * FROM questionnaire WHERE titre = '".$questionnaire."'"))) {
						if ($wpdb->insert("questionnaire", $questionnaire_info)) {
							$id = $wpdb->get_results("SELECT * FROM questionnaire WHERE titre = '".$questionnaire."'")[0]->id;
							$question_info = [
								"idfrom" => intval($id),
								"contenu" => $question
							];
							if ($wpdb->insert("question", $question_info)) {
								wp_die("Votre questionnaire '$questionnaire' a été créé avec succès ! <a href='/wordpress/wordpress/wp-admin/'>Retour</a>");
							}
							else {
								wp_die("Votre questionnaire '$questionnaire' n'a pas pu être créé pour une raison inconnue. <a href='/wordpress/wordpress/wp-admin/'>Retour</a>");
							}
						}
						else {
							wp_die("Le questionnaire n'a pas pu être créé pour une raison inconnue. <a href='/wordpress/wordpress/wp-admin/'>Retour</a>");
						}
					}
					else {
						wp_die("Le questionnaire n'a pas pu être créé car le titre du questionnaire est déjà utilisé par un autre questionnaire. <a href='/wordpress/wordpress/wp-admin/'>Retour</a>");
					}
				}
				else {
					wp_die("Le questionnaire n'a pas pu être créé l'un des champs étaient vides. <a href='/wordpress/wordpress/wp-admin/'>Retour</a>");
				}
			}
		}
	}
}
function content_updateuser_menu_page() {
	global $wpdb;
	if (isset($_POST["id"])) {
		$_POST["id"] = intval($_POST["id"]);
		$p = $wpdb->get_results("SELECT * FROM utilisateur where id = '".$_POST["id"]."'")[0];
	}
	?>
		<h1 class="wp-heading-inline">Modification d'un utilisateur (Version couteau-suisse)</h1>
		<form method="post" action="#">
			<input type="hidden" name="update_users" value="update">
			<input type="text" name="nom" required="" placeholder="Modifier..." value="<?php if (isset($p)) echo $p->nom; ?>">
			<input type="email" name="mail" required="" placeholder="Mail utilisateur existant (Ne sera pas modifié)" value="<?php if (isset($p)) echo $p->mail; ?>">
			<?php submit_button("Modifier l'utilisateur (CS)"); ?>
		</form>
	<?php
}
function content_deleteuser_menu_page() {
	global $wpdb;
	if (isset($_POST["id"])) {
		$_POST["id"] = intval($_POST["id"]);
		$p = $wpdb->get_results("SELECT * FROM utilisateur where id = '".$_POST["id"]."'")[0];
	}
	?>
		<h1 class="wp-heading-inline">Supression d'un utilisateur (Version couteau-suisse)</h1>
		<form method="post" action="#">
			<input type="hidden" name="delete_users" value="delete">
			<input type="email" name="mail" required="" placeholder="Utilisateur à supprimer" value="<?php if (isset($p)) echo $p->mail; ?>">
			<?php submit_button("Supprimer l'utilisateur (CS)"); ?>
		</form>
	<?php
}
function content_createuser_menu_page() {
	?>
		<h1 class="wp-heading-inline">Création d'un utilisateur (Version couteau-suisse)</h1>
		<form method="post" action="#">
			<input type="hidden" name="create_users" value="create">
			<input type="text" name="nom" required="" placeholder="Nom utilisateur...">
			<input type="email" name="mail" required="" placeholder="Mail utilisateur...">
			<?php submit_button("Créer l'utilisateur (CS)"); ?>			
		</form>
	<?php
}
function content_userslist_menu_page() {
	global $wpdb;
	$userlist = $wpdb->get_results("SELECT * FROM utilisateur");
	?>
	<h1 class="wp-heading-inline">Liste des utilisateurs (Version couteau-suisse)</h1>
	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th class="manage-column">Nom</th>
				<th class="manage-column">Mail</th>
			</tr>
		</thead>
		<tbody>
	<?php
	if (empty($userlist)) {
		?><tr><th class="manage-column">Aucun utilisateur enregistré</th></tr><?php
	}
	else {
		foreach ($userlist as $key => $user) {
			?>
				<tr>
					<th class="manage-column"><?=$user->nom?> 
						<form method="post" action="admin.php?page=couteau-suisse%2Fcouteau-suisse-user-update" style="display: inline;">
							<input type="hidden" name="id" value="<?= $user->id ?>">
							<input type="submit" value="Modifier">
						</form>
						<form method="post" action="admin.php?page=couteau-suisse%2Fcouteau-suisse-user-delete" style="display: inline;">
							<input type="hidden" name="id" value="<?= $user->id ?>">
							<a><input type="submit" value="Supprimer"></a>
						</form>
					</th>
					<th class="manage-column"><?=$user->mail?></th>
				</tr>
			<?php
		}
	}
	?>
		</tbody>
	</table>
	<?php
}
function content_questionnairelist_menu_page() {
	global $wpdb;
	$questionnairelist = $wpdb->get_results("SELECT * FROM questionnaire");
	?>
	<h1 class="wp-heading-inline">Liste des questionnaires (Version couteau-suisse)</h1>
	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th class="manage-column">Titre</th>
				<th class="manage-column">Auteur</th>
			</tr>
		</thead>
		<tbody>
	<?php
	if (empty($questionnairelist)) {
		?><tr><th class="manage-column">Aucun questionnaire</th></tr><?php
	}
	else {
		foreach ($questionnairelist as $key => $questionnaire) {
			?>
				<tr>
					<th class="manage-column"><?=$questionnaire->titre?> 
						<form method="post" action="admin.php?page=couteau-suisse%2Fcouteau-suisse-questions-add" style="display: inline;">
							<input type="hidden" name="questionnaire" value="<?= $questionnaire->titre ?>">
							<input type="submit" value="Ajouter une question">
						</form>
						<form method="post" action="admin.php?page=couteau-suisse%2Fcouteau-suisse-questions-update" style="display: inline;">
							<input type="hidden" name="questionnaire" value="<?= $questionnaire->titre ?>">
							<input type="submit" value="Modifier">
						</form>
						<form method="post" action="admin.php?page=couteau-suisse%2Fcouteau-suisse-questions-delete" style="display: inline;">
							<input type="hidden" name="questionnaire" value="<?= $questionnaire->titre ?>">
							<a><input type="submit" value="Supprimer"></a>
						</form>
					</th>
					<th class="manage-column"><?=$questionnaire->auteur?></th>
				</tr>
			<?php
		}
	}
	?>
		</tbody>
	</table>
	<?php
}
function content_addquestion_menu_page() {
	global $wpdb;
	$questionnaire = "";
	if (isset($_POST["questionnaire"])) {
		$questionnaire = trim($_POST["questionnaire"]);
	}
	?>
		<h1 class="wp-heading-inline">Ajout d'une question (Version couteau-suisse)</h1>
		<form method="post" action="#">
			<input type="hidden" name="add_question" value="update">
			<select name="questionnaire">
				<?php if ($questionnaire != "") { ?>
				<option value="<?=$questionnaire?>"><?=$questionnaire?></option>
				<?php
					}
					if ($results = $wpdb->get_results("SELECT * FROM questionnaire WHERE titre != '".$questionnaire."'")) {
						foreach ($results as $key => $value) {
							?>
								<option value="<?=$value->titre?>"><?=$value->titre?></option>
							<?php
						}
					}
					else {
						?>
							<option>Pas de questionnaire</option>
						<?php
					} 
				?>
			</select>
			<input type="text" name="question" required="" placeholder="Question à poser">
			<?php submit_button("Ajouter la question (CS)"); ?>
		</form>
	<?php
}
function content_updatequestionnaire_menu_page() {
	global $wpdb;
	$questionnaire = "";
	if (isset($_POST["questionnaire"])) {
		$questionnaire = trim($_POST["questionnaire"]);
	}
	?>
		<h1 class="wp-heading-inline">Modification d'un questionnaire (Version couteau-suisse)</h1>
		<form method="post" action="#">
			<input type="hidden" name="update_question" value="update">
			<select name="oldquestionnaire">
				<?php if ($questionnaire != "") { ?>
				<option value="<?=$questionnaire?>"><?=$questionnaire?></option>
				<?php
					}
					if ($results = $wpdb->get_results("SELECT * FROM questionnaire WHERE titre != '".$questionnaire."'")) {
						foreach ($results as $key => $value) {
							?>
								<option value="<?=$value->titre?>"><?=$value->titre?></option>
							<?php
						}
					}
					else {
						?>
							<option>Pas de questionnaire</option>
						<?php
					} 
				?>
			</select>
			<input type="text" name="newquestionnaire" required="" placeholder="Nouveau nom pour le questionnaire">
			<?php submit_button("Modifier le questionnaire (CS)"); ?>
		</form>
	<?php
}
function content_deletequestionnaires_menu_page() {
	global $wpdb;
	if (isset($_POST["questionnaire"])) {
		$questionnaire = trim($_POST["questionnaire"]);
	}
	?>
		<h1 class="wp-heading-inline">Supression d'un questionnaire (Version couteau-suisse)</h1>
		<form method="post" action="#">
			<input type="hidden" name="delete_question" value="delete">
			<input type="text" name="questionnaire" required="" placeholder="Questionnaire à supprimer" value="<?php if (isset($questionnaire)) echo $questionnaire; ?>">
			<?php submit_button("Supprimer le questionnaire (CS)"); ?>
		</form>
	<?php
}
function content_createquestionnaire_menu_page() {
	?>
	<div>
		<h1 class="wp-heading-inline">Création d'un questionnaire (Version couteau-suisse)</h1>
		<form method="post" action="#">
			<input type="hidden" name="create_question" value="update">
			<input type="text" name="questionnaire" placeholder="Nom du questionnaire...">
			<input type="text" name="question" placeholder="Première question">
			<?php submit_button("Créer le questionnaire (CS)"); ?>	
		</form>
	</div>
	<?php
}
add_action("admin_menu","exec_couteau_suisse");