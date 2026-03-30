/*** JS POPUP ***/

const popupHTML = `
<div id="jsPopup" class="popup">
  <div class="popup-content">
	<h2 id="popup-title"></h2>
	<span class="close">&times;</span>
	<p id="popup-content"></p>
  </div>
</div>
`;

const parentElement = document.body;
parentElement.insertAdjacentHTML('beforeend', popupHTML);