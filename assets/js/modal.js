export default class Modal {
  constructor(element) {
    this.element = element; // Élément de la modale
    this.confirmButton = this.element.querySelector("[data-action]");
    this.closeButtons = this.element.querySelectorAll("[data-close]");

    // Écouteurs d'événements pour fermer la modale
    this.closeButtons.forEach(button => {
      button.addEventListener("click", () => this.close());
    });

    // Gestion de la fermeture en cliquant en dehors de la modale
    this.element.addEventListener("click", (event) => {
      if (event.target === this.element) {
        this.close();
      }
    });
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
    if (this.confirmButton) {
      this.confirmButton.setAttribute("data-action", action);
    }
  }

  setContent(content) {
    const contentElement = this.element.querySelector("[data-content]");
    if (contentElement) {
      contentElement.innerHTML = content;
    }
  }

  setTitle(title) {
    const contentElement = this.element.querySelector("[data-title]");
    if (contentElement) {
      contentElement.innerHTML = title;
    }
  }

  setId(id) {
    this.element.id = id;
  }
}
