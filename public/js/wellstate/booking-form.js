import { state, renderSummary, resetPromotion } from "./booking-summary";

const qs = (s, p = document) => p.querySelector(s);
const qsa = (s, p = document) => [...p.querySelectorAll(s)];

function generateUID() {
	return "g_" + Math.random().toString(36).substring(2, 10);
}

export function initBookingForm() {
	const guestCountInput = qs("#guest-count");
	const guestContainer = qs("#guest-services");
	const guestTemplate = qs("#guest-template");
	const serviceTemplate = qs("#service-template");

	function renderGuests(count) {
		guestContainer.innerHTML = "";

		resetPromotion();

		for (let i = 0; i < count; i++) {
			const fragment = guestTemplate.content.cloneNode(true);

			fragment.querySelector(".guest-index").textContent = i + 1;

			fragment.querySelector(".guest-uid-input").value = generateUID();

			fragment.querySelectorAll("[name]").forEach((el) => {
				el.name = el.name.replace("__index__", i);
			});

			const guestEl = fragment.querySelector(".guest-item");
			const servicesWrapper = guestEl.querySelector(".services-wrapper");

			addService(servicesWrapper, i);

			guestEl
				.querySelector(".add-service")
				.addEventListener("click", () => {
					addService(servicesWrapper, i);
					resetPromotion();
				});

			guestContainer.appendChild(fragment);
		}
	}

	function addService(wrapper, guestIndex) {
		const serviceIndex = wrapper.children.length;
		const fragment = serviceTemplate.content.cloneNode(true);

		fragment.querySelectorAll("[name]").forEach((el) => {
			el.name = el.name.replace(
				"__SERVICE_NAME__",
				`guests[${guestIndex}][services][${serviceIndex}]`,
			);
		});

		fragment
			.querySelector(".remove-service")
			.addEventListener("click", function () {
				this.closest(".service-item").remove();

				resetPromotion();
				recalcServices();
			});

		wrapper.appendChild(fragment);
	}

	function recalcServices() {
		let subtotal = 0;
		state.services = [];

		qsa(".service-select").forEach((select) => {
			const opt = select.selectedOptions[0];
			if (!opt || !opt.dataset.price) return;

			const price = Number(opt.dataset.price);

			state.services.push({
				id: opt.value,
				price: price,
			});

			subtotal += price;
		});

		state.subtotal = subtotal;

		renderSummary();
	}

	guestCountInput?.addEventListener("input", function () {
		const count = Math.max(1, parseInt(this.value) || 1);

		state.guests = count;

		renderGuests(count);

		renderSummary();
	});

	document.addEventListener("change", function (e) {
		if (!e.target.classList.contains("service-select")) return;

		resetPromotion();

		recalcServices();
	});

	renderGuests(parseInt(guestCountInput?.value) || 1);
}
