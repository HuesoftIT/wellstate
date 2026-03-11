export const state = {
	branch: null,
	date: null,
	time: null,
	guests: 1,
	room: null,
	roomFee: 0,
	subtotal: 0,
	total: 0,
	discount: 0,
	promotionId: null,
	services: [],
	branchId: null,
	branchRoomTypeId: null,
	phone: null,
};

const qs = (s, p = document) => p.querySelector(s);
const qsa = (s, p = document) => [...p.querySelectorAll(s)];

export const money = (v) => new Intl.NumberFormat("vi-VN").format(v) + "đ";

export function resetPromotion() {
	state.discount = 0;
	state.promotionId = null;

	const promoInput = qs("#promotion_code");
	if (promoInput) promoInput.value = "";

	renderSummary();
}

function setText(selector, value) {
	const el = qs(selector);
	if (el) el.textContent = value;
}

export function renderSummary() {
	setText("#summary-branch", state.branch || "-");
	setText("#summary-date", state.date || "-");
	setText("#summary-time", state.time || "-");
	setText("#summary-guests", state.guests + " khách");
	setText("#summary-room", state.room || "-");

	setText("#summary-subtotal", money(state.subtotal));

	setText(
		"#summary-room-fee",
		state.roomFee ? `+${money(state.roomFee)}` : "0đ",
	);

	setText(
		"#summary-discount",
		state.discount ? `-${money(state.discount)}` : "0đ",
	);

	const total =
		state.subtotal - state.discount > 0
			? state.subtotal - state.discount + state.roomFee
			: state.roomFee;

	state.total = total;

	setText("#summary-total", money(state.total));
}
