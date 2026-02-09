function formatVND(value) {
	value = value.replace(/\D/g, "");
	return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function bindMoneyInput() {
	document.querySelectorAll(".money-input").forEach((input) => {
		input.addEventListener("input", function () {
			const raw = this.value.replace(/\./g, "");
			this.value = formatVND(raw);

			const hiddenInput = document.getElementById(
				this.id.replace("_display", "")
			);

			if (hiddenInput) {
				hiddenInput.value = raw;
			}
		});
	});
}

document.addEventListener("DOMContentLoaded", bindMoneyInput);

