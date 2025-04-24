import MessageBuilder from './messageBuilder'
export default class RequestManager {
  static async confirmRequest(data) {
    try {
      const response = await fetch('/request/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ data: data })
      });

      if (!response.ok) {
        throw new Error(`Une erreur est survenue: ${response.status}`);
      }else{
        const result = await response.json();
        MessageBuilder.displayMessage('success',result);
      }


      //alert(result.message); // Affiche le message de Symfony
    } catch (error) {
      console.log(error);
      MessageBuilder.displayMessage('danger',error);
    }
  }
}
