/*** MAIN JS ***/

class AjaxRequest {
	
	/**
	 * Classe AJAX JS
	 * Dépendance : JQuery
	 * https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js
	 * Utilise un debugger en interne, dépendances :
	 * popup.js | popup.css
	 * Activation :
	 * const ajaxDebug = true;
	 * Utilisation  :
	 * @param url
	 * @param method POST | GET
	 * @param array data
	 * const request = new AjaxRequest(url, 'POST', data);
	 *		request.send (
	 *			//AJAX : success :
	 *			(response) => { },
	 *			//AJAX : complete :
	 *			(response) => { }
	 *		);
	 */
	
	constructor(url, type, data, fullDebug=true) {
		this.url = url;
		this.type = type;
		this.data = data;
		this.dbug = ajaxDebug;
		this.verbose = fullDebug; //false -> masquer le popup sur le DOM, laisser le console.log
		
		if(this.dbug) {
			this.log = '->Début de la requête AJAX\n';
			if(this.verbose) {
				this.popup = document.getElementById('jsPopup');
				this.dbugContent = document.getElementById('popup-content');
				this.closeBtn = document.getElementsByClassName('close')[0];
				document.getElementById('popup-title').textContent = 'AJAX debugger';
				
				this.popup.addEventListener('click', (event) => {
					if (event.target === this.closeBtn || event.target === this.popup) {
						$('body').removeClass('popup-open');
						this.popup.style.display = 'none';
					}
				});
				
			}
		}
	}

	send(successCallback, completeCallback) {

		if(this.dbug) {
			this.log += '->URL:\n' + this.url + '\n';
			this.log += '->Type:\n' + this.type + '\n';
			this.log += '->Données expédiées:\n' + JSON.stringify(this.data, null, 2) + '\n';
		}

		$.ajax({
			url: this.url,
			type: this.type,
			data: this.data,
			success: (response) => {
				if(this.dbug) {
					this.log += '->Callback PHP:\n' + JSON.stringify(response, null, 2) + '\n';
				}
				successCallback(response);
			},
			complete: () => {
				if(this.dbug) {
					this.log += '->Requête AJAX terminée\n';
					console.log(this.log);
					if(this.verbose) {
						const htmlContent = this.log.replace(/->(.*)\n/g, "<li><b>$1</b></li>");
						this.dbugContent.innerHTML = htmlContent.replace(/\n/g, "<br/>");
						$('body').addClass('popup-open');
						this.popup.style.display = "block";
					}
				}
				completeCallback();
			},
			error: (jqXHR, textStatus, errorThrown) => {
				console.error('Erreur lors de la requête: ', textStatus, errorThrown);
				console.error('Réponse du serveur: ', jqXHR.responseText);
			}
		});
	}
}