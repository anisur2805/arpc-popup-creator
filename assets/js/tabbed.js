window.addEventListener("load", function () {
	var tabs = this.document.querySelectorAll(".arpc_tabbed_wrapper ul > li");

	for (var i = 0; i < tabs.length; i++) {
		tabs[i].addEventListener("click", switchTab);
	}

	function switchTab(e) {
		e.preventDefault();

		var li = document.querySelector(".arpc_tabbed_wrapper ul li.active").classList.remove("active");

		document.querySelector(".arpc_tabbed_wrapper .tab-pane.active").classList.remove("active");

		var clickedTab = e.currentTarget;
		var anchor = e.target;
		var activePanelID = anchor.getAttribute("href");

		clickedTab.classList.add("active");
		document.querySelector(activePanelID).classList.add("active");
	}
});
