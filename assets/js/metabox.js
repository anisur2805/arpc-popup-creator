var frame;
; (function ($) {
	// Set all variables to be used in scope
	var frame,
		metaBox = $("#myImageMetaBox"), // Your meta box id here
		addImgLink = metaBox.find("#arpc_upload_image"),
		delImgLink = metaBox.find("#arpc_delete_custom_img"),
		imgContainer = metaBox.find("#arpc_image_container"),
		imgIdInput = metaBox.find("#arpc_image_id"),
		imgURLInput = metaBox.find("#arpc_image_url");

	$(document).ready(function () {

		// for image upload
		var image_url = $("#arpc_image_url").val();
		if (image_url && image_url !== undefined) {
			imgContainer.html(`<img src='${image_url}' />`);
		}

		// upload image
		addImgLink.on("click", function (event) {
			event.preventDefault();

			// If the media frame already exists, reopen it.
			if (frame) {
				frame.open();
				return;
			}

			// Create a new media frame
			frame = wp.media({
				title: "Select Image",
				button: {
					text: "Insert Image",
				},
				multiple: false, // Set to true to allow multiple files to be selected
			});

			// When an image is selected in the media frame...
			frame.on("select", function () {
				// Get media attachment details from the frame state
				let attachment = frame.state().get("selection").first().toJSON();

				if (attachment) {
					// Send the attachment URL to our custom image input field.
					imgContainer.html(`<img src='${attachment.url}' />`);
					imgIdInput.val(attachment.id);
					imgURLInput.val(attachment.url);

					// Hide the add image link
					addImgLink.addClass("hidden");

					// Unhide the remove image link
					delImgLink.removeClass("hidden");
				}
			});

			frame.open();
			return false;
		});

		// Toggle auto hide based on checkbox
		var auto_hide_input = document.querySelector(".auto_hide_gp input");
		var auto_hide_in_gp = document.querySelector(".auto_hide_in_gp");
		auto_hide_in_gp ? auto_hide_in_gp.style.display = 'none' : '';

		auto_hide_input && (
			auto_hide_input.addEventListener("change", function () {
				if (this.checked) {
					auto_hide_in_gp.style.display = 'flex';
				} else {
					auto_hide_in_gp.style.display = 'none';
				}
			})
		);
	});

})(jQuery);
