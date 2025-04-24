export default class MessageBuilder {
  /**
   * @param type
   * @param message
   */
  static displayMessage(type, message) {
    const placeHolder = document.getElementById('alert')

    const wrapper = document.createElement('div')
    wrapper.innerHTML = [
      `<div class="alert alert-${type} alert-dismissible" role="alert">`,
      `   <div>${message}</div>`,
      '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
      '</div>'
    ].join('')

    placeHolder.append(wrapper)
  }
}
