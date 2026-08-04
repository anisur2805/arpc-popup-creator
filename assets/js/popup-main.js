;(function ($) {
	var popupInstances = []
	var activePopup = null

	function parseConfig(element) {
		var rawConfig = element.getAttribute("data-popup-config")
		if (!rawConfig) {
			return null
		}

		try {
			return JSON.parse(rawConfig)
		} catch (error) {
			return null
		}
	}

	function getStorageKey(instance) {
		return "arpc-popup-" + instance.id
	}

	function getSeenAt(instance) {
		try {
			return parseInt(window.localStorage.getItem(getStorageKey(instance)), 10) || 0
		} catch (error) {
			return 0
		}
	}

	function markSeen(instance) {
		if (instance.settings.periodicity === "every_time") {
			return
		}

		try {
			window.localStorage.setItem(getStorageKey(instance), String(Date.now()))
		} catch (error) {}
	}

	function periodToMilliseconds(settings) {
		var value = parseInt(settings.period_value || 1, 10)
		var unit = settings.period_unit || "hour"
		var multipliers = {
			minute: 60 * 1000,
			hour: 60 * 60 * 1000,
			day: 24 * 60 * 60 * 1000,
			week: 7 * 24 * 60 * 60 * 1000,
			month: 30 * 24 * 60 * 60 * 1000,
		}

		// Falls back to hours to match the PHP default in Popup_Settings::defaults().
		return (multipliers[unit] || multipliers.hour) * Math.max(value, 1)
	}

	function wasAlreadyShown(instance) {
		var seenAt = getSeenAt(instance)
		if (!seenAt) {
			return false
		}

		if (instance.settings.periodicity === "once_only") {
			return true
		}

		if (instance.settings.periodicity === "once_per_period") {
			return Date.now() - seenAt < periodToMilliseconds(instance.settings)
		}

		return false
	}

	function currentDevice() {
		var width = window.innerWidth
		if (width <= 767) {
			return "mobile"
		}

		if (width <= 1024) {
			return "tablet"
		}

		return "desktop"
	}

	function shouldHideOnDevice(instance) {
		var hiddenDevices = instance.hideDevices || []
		return hiddenDevices.indexOf(currentDevice()) !== -1
	}

	function closeOtherPopups(nextPopupId) {
		popupInstances.forEach(function (instance) {
			if (instance.id !== nextPopupId && instance.isOpen) {
				closePopup(instance)
			}
		})
	}

	function updateBodyScrollState() {
		var overlayTypes = ["modal", "fullscreen"]
		var shouldLock = popupInstances.some(function (instance) {
			var popupType = instance.settings.popup_type || "modal"
			return instance.isOpen && instance.settings.prevent_scroll && overlayTypes.indexOf(popupType) !== -1
		})

		document.body.classList.toggle("arpc-popup-open", shouldLock)
	}

	function openPopup(instance) {
		if (!instance || instance.isOpen || shouldHideOnDevice(instance) || wasAlreadyShown(instance)) {
			return
		}

		closeOtherPopups(instance.id)

		instance.element.hidden = false
		instance.element.classList.remove("is-closing")
		instance.element.classList.add("is-opening", "is-open")
		instance.isOpen = true
		activePopup = instance

		window.setTimeout(function () {
			instance.element.classList.remove("is-opening")
		}, 260)

		if (instance.settings.close_back) {
			try {
				window.history.pushState({ arpcPopup: instance.id }, document.title)
				instance.historyPushed = true
			} catch (error) {
				instance.historyPushed = false
			}
		}

		if (parseInt(instance.settings.auto_close_delay || 0, 10) > 0) {
			instance.autoCloseTimer = window.setTimeout(function () {
				closePopup(instance)
			}, parseInt(instance.settings.auto_close_delay, 10) * 1000)
		}

		markSeen(instance)
		updateBodyScrollState()
	}

	function closePopup(instance) {
		if (!instance || !instance.isOpen) {
			return
		}

		if (instance.autoCloseTimer) {
			window.clearTimeout(instance.autoCloseTimer)
			instance.autoCloseTimer = null
		}

		instance.element.classList.remove("is-opening")
		instance.element.classList.add("is-closing")
		instance.isOpen = false

		window.setTimeout(function () {
			instance.element.classList.remove("is-open", "is-closing")
			instance.element.hidden = true
		}, 260)

		if (activePopup && activePopup.id === instance.id) {
			activePopup = null
		}

		updateBodyScrollState()
	}

	function triggerOpenWithDelay(instance) {
		if (instance.pendingTimer) {
			window.clearTimeout(instance.pendingTimer)
		}

		instance.pendingTimer = window.setTimeout(function () {
			openPopup(instance)
		}, Math.max(parseInt(instance.settings.open_delay || 0, 10), 0) * 1000)
	}

	function matchesSelectorList(target, selectors) {
		if (!selectors || !selectors.length) {
			return null
		}

		for (var i = 0; i < selectors.length; i++) {
			var selector = selectors[i]
			if (!selector) {
				continue
			}

			try {
				var matched = target.closest(selector)
				if (matched) {
					return matched
				}
			} catch (error) {}
		}

		return null
	}

	function bindGlobalClickHandlers() {
		$(document).on("click", function (event) {
			var target = event.target

			popupInstances.forEach(function (instance) {
				var triggerElement = matchesSelectorList(target, instance.openSelectors)
				if (triggerElement && instance.settings.trigger_mode === "click") {
					if (instance.settings.disable_link) {
						event.preventDefault()
					}

					triggerOpenWithDelay(instance)
				}

				if (instance.settings.close_selector) {
					try {
						var customCloser = target.closest(instance.settings.close_selector)
						if (customCloser) {
							closePopup(instance)
						}
					} catch (error) {}
				}

				if (instance.isOpen) {
					if (target.closest("[data-arpc-close]")) {
						closePopup(instance)
					}

					if (target.hasAttribute("data-arpc-overlay") && instance.settings.close_overlay) {
						closePopup(instance)
					}
				}
			})
		})
	}

	function bindKeyboardHandler() {
		$(document).on("keydown", function (event) {
			if (event.key === "Escape" && activePopup) {
				closePopup(activePopup)
			}
		})
	}

	function bindPopStateHandler() {
		window.addEventListener("popstate", function () {
			if (activePopup && activePopup.settings.close_back) {
				closePopup(activePopup)
			}
		})
	}

	function bindLoadTrigger(instance) {
		triggerOpenWithDelay(instance)
	}

	function bindScrollTrigger(instance) {
		var handled = false
		var depth = parseInt(instance.settings.scroll_depth || 50, 10) / 100

		$(window).on("scroll.arpcPopup" + instance.id, function () {
			if (handled || instance.isOpen || wasAlreadyShown(instance)) {
				return
			}

			var scrollTop = window.scrollY || document.documentElement.scrollTop
			var viewportHeight = window.innerHeight
			var totalHeight = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
			var progress = (scrollTop + viewportHeight) / totalHeight

			if (progress >= depth) {
				handled = true
				triggerOpenWithDelay(instance)
			}
		})
	}

	function bindExitTrigger(instance) {
		var handled = false

		document.addEventListener("mouseout", function (event) {
			if (handled || instance.isOpen || wasAlreadyShown(instance)) {
				return
			}

			if (event.clientY <= 0) {
				handled = true
				triggerOpenWithDelay(instance)
			}
		})
	}

	function bindInactivityTrigger(instance) {
		var timer
		var delay = parseInt(instance.settings.open_delay || 5, 10)

		function restartTimer() {
			if (timer) {
				window.clearTimeout(timer)
			}

			timer = window.setTimeout(function () {
				openPopup(instance)
			}, Math.max(delay, 1) * 1000)
		}

		$(document).on("mousemove keydown scroll touchstart", restartTimer)
		restartTimer()
	}

	function padNumber(value) {
		return value < 10 ? "0" + value : String(value)
	}

	function initCountdown(element) {
		var targetRaw = element.getAttribute("data-countdown-target")
		var target = targetRaw ? new Date(targetRaw).getTime() : 0
		if (!target) {
			return
		}

		var daysEl = element.querySelector("[data-countdown-days]")
		var hoursEl = element.querySelector("[data-countdown-hours]")
		var minutesEl = element.querySelector("[data-countdown-minutes]")
		var secondsEl = element.querySelector("[data-countdown-seconds]")
		var timer = null

		function render() {
			var diff = target - Date.now()

			if (diff <= 0) {
				element.classList.add("is-expired")

				var expired = element.getAttribute("data-countdown-expired")
				if (expired) {
					element.textContent = ""
					var message = document.createElement("p")
					message.className = "arpc-countdown__expired"
					message.textContent = expired
					element.appendChild(message)
				} else {
					if (daysEl) daysEl.textContent = "00"
					if (hoursEl) hoursEl.textContent = "00"
					if (minutesEl) minutesEl.textContent = "00"
					if (secondsEl) secondsEl.textContent = "00"
				}

				if (timer) {
					window.clearInterval(timer)
				}
				return
			}

			var totalSeconds = Math.floor(diff / 1000)

			if (daysEl) daysEl.textContent = padNumber(Math.floor(totalSeconds / 86400))
			if (hoursEl) hoursEl.textContent = padNumber(Math.floor((totalSeconds % 86400) / 3600))
			if (minutesEl) minutesEl.textContent = padNumber(Math.floor((totalSeconds % 3600) / 60))
			if (secondsEl) secondsEl.textContent = padNumber(totalSeconds % 60)
		}

		render()
		timer = window.setInterval(render, 1000)
	}

	function initCountdowns() {
		$("[data-arpc-countdown]").each(function () {
			initCountdown(this)
		})
	}

	function setupTrigger(instance) {
		switch (instance.settings.trigger_mode) {
			case "click":
				break
			case "scroll":
				bindScrollTrigger(instance)
				break
			case "exit":
				bindExitTrigger(instance)
				break
			case "inactivity":
				bindInactivityTrigger(instance)
				break
			case "load":
			default:
				bindLoadTrigger(instance)
				break
		}
	}

	$(document).ready(function () {
		$(".arpc-popup-creator").each(function () {
			var config = parseConfig(this)
			if (!config || !config.settings) {
				return
			}

			var instance = {
				id: config.id,
				element: this,
				settings: config.settings,
				triggerKey: config.triggerKey,
				hideDevices: config.hideDevices || [],
				openSelectors: config.openSelectors || [],
				isOpen: false,
				autoCloseTimer: null,
				pendingTimer: null,
				historyPushed: false,
			}

			if (!shouldHideOnDevice(instance)) {
				setupTrigger(instance)
			}

			popupInstances.push(instance)
		})

		initCountdowns()

		if (!popupInstances.length) {
			return
		}

		bindGlobalClickHandlers()
		bindKeyboardHandler()
		bindPopStateHandler()
	})
})(jQuery)
