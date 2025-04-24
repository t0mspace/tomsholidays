import MessageBuilder from './messageBuilder'
export default class RequestManager {
  static async confirmRequest(data) {
      await fetch('/request/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ data: data })
      })
      .then(response => {
        if(!response.ok) {
          throw new Error(`Une erreur est survenue: ${response.status}`);
        }
        console.log(response)
        return response.json();
      })
        .then(result => {
          MessageBuilder.displayMessage('success',result.message);
        })
      .catch(error => {
        MessageBuilder.displayMessage('danger',error);
      })
  }
}
