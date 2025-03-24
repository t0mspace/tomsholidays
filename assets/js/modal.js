export default class Modal {
  constructor(element) {
    this.element = element; // Élément de la modale
    this.confirmButton = this.element.querySelector("[data-modal-target='confirmButton']");
  }

  open() {
    this.element.classList.add("show");
    this.element.style.display = "block";
    document.body.classList.add("modal-open");
  }

  close() {
    this.element.classList.remove("show");
    this.element.style.display = "none";
    document.body.classList.remove("modal-open");
  }

  setAction(action) {
    this.confirmButton.dataset.action = action;
  }

  setContent(content) {
    this.element.querySelector("[data-modal-target='content']").innerHTML = content;
  }
}
