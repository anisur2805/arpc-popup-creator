;(function ($) {
	var frame

	function updateImagePreview(metaBox) {
		var imageUrlInput = metaBox.find("#arpc_image_url")
		var imageContainer = metaBox.find("#arpc_image_container")
		var removeButton = metaBox.find("#arpc_delete_custom_img")
		var imageUrl = imageUrlInput.val()

		if (imageUrl) {
			imageContainer.html("<img src='" + imageUrl + "' alt='' />")
			removeButton.removeClass("hidden")
			return
		}

		imageContainer.empty()
		removeButton.addClass("hidden")
	}

	function bindImageUploader() {
		var metaBox = $("#myImageMetaBox")
		if (!metaBox.length) {
			return
		}

		var addImgLink = metaBox.find("#arpc_upload_image")
		var delImgLink = metaBox.find("#arpc_delete_custom_img")
		var imgContainer = metaBox.find("#arpc_image_container")
		var imgIdInput = metaBox.find("#arpc_image_id")
		var imgURLInput = metaBox.find("#arpc_image_url")

		updateImagePreview(metaBox)

		addImgLink.on("click", function (event) {
			event.preventDefault()

			if (frame) {
				frame.open()
				return
			}

			frame = wp.media({
				title: "Select Image",
				button: {
					text: "Insert Image",
				},
				multiple: false,
			})

			frame.on("select", function () {
				var attachment = frame.state().get("selection").first().toJSON()

				if (!attachment) {
					return
				}

				imgContainer.html("<img src='" + attachment.url + "' alt='' />")
				imgIdInput.val(attachment.id)
				imgURLInput.val(attachment.url)
				delImgLink.removeClass("hidden")
			})

			frame.open()
		})

		delImgLink.on("click", function (event) {
			event.preventDefault()
			imgContainer.empty()
			imgIdInput.val("")
			imgURLInput.val("")
			delImgLink.addClass("hidden")
		})
	}

	function bindTabs(container) {
		container.find("[data-arpc-tab]").on("click", function () {
			var tab = $(this).data("arpcTab")

			container.find("[data-arpc-tab]").removeClass("is-active")
			container.find("[data-arpc-panel]").removeClass("is-active")

			$(this).addClass("is-active")
			container.find('[data-arpc-panel="' + tab + '"]').addClass("is-active")
		})
	}

	function bindVisibilityToggles(container) {
		function toggleTriggerSections(triggerMode) {
			var isClick = triggerMode === "click"
			container.find('[data-trigger-section="click"]').toggleClass("is-hidden", !isClick)
			container.find('[data-trigger-section="timed"]').toggleClass("is-hidden", isClick)
		}

		var checkedTrigger = container.find('input[name="arpc_popup_settings[trigger_mode]"]:checked').val() || "load"
		toggleTriggerSections(checkedTrigger)

		container.on("change", 'input[name="arpc_popup_settings[trigger_mode]"]', function () {
			toggleTriggerSections($(this).val())
		})

		container.on("change", 'input[name="arpc_popup_settings[periodicity]"]', function () {
			var isVisible = $(this).val() === "once_per_period"
			container.find("[data-periodicity-options]").toggleClass("is-hidden", !isVisible)
		})

		container.on("change", 'input[name="arpc_popup_settings[activity_mode]"]', function () {
			var isVisible = $(this).val() === "certain_period"
			container.find("[data-activity-options]").toggleClass("is-hidden", !isVisible)
		})

		container.on("change", "#arpc-overlay-blur", function () {
			container.find("[data-overlay-blur-amount]").toggleClass("is-hidden", !this.checked)
		})
	}

	function bindFieldResets(container) {
		container.on("click", "[data-reset-field]", function () {
			var selector = $(this).data("resetField")
			var field = container.find(selector)
			if (!field.length) {
				return
			}

			field.val(field.data("defaultValue") || "")
		})
	}

	function bindCopyTrigger(container) {
		container.on("click", "[data-copy-trigger]", function () {
			var input = container.find("#arpc-manual-trigger")
			if (!input.length) {
				return
			}

			input.trigger("select")

			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(input.val())
				return
			}

			document.execCommand("copy")
		})
	}

	function toggleLocationTargets(rule) {
		var selectedType = rule.find("[data-location-type]").val()
		rule.find("[data-location-targets]").addClass("is-hidden").find("select").prop("disabled", true)
		rule.find('[data-location-targets="' + selectedType + '"]').removeClass("is-hidden").find("select").prop("disabled", false)
	}

	function bindLocationRules(container) {
		var rulesWrapper = container.find("[data-location-rules]")
		var template = wp.template("arpc-location-rule")

		rulesWrapper.find("[data-location-rule]").each(function () {
			toggleLocationTargets($(this))
		})

		rulesWrapper.on("change", "[data-location-type]", function () {
			toggleLocationTargets($(this).closest("[data-location-rule]"))
		})

		container.on("click", "[data-add-location]", function () {
			var index = rulesWrapper.find("[data-location-rule]").length
			rulesWrapper.append(template({ index: index }))
			toggleLocationTargets(rulesWrapper.find("[data-location-rule]").last())
		})

		rulesWrapper.on("click", "[data-remove-location]", function () {
			var rules = rulesWrapper.find("[data-location-rule]")
			if (rules.length === 1) {
				return
			}

			$(this).closest("[data-location-rule]").remove()
		})
	}

	$(document).ready(function () {
		var container = $("[data-arpc-metabox]")
		if (!container.length) {
			return
		}

		bindImageUploader()
		bindTabs(container)
		bindVisibilityToggles(container)
		bindFieldResets(container)
		bindCopyTrigger(container)
		bindLocationRules(container)
	})
})(jQuery)
