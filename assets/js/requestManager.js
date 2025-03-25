export default class RequestManager {
  static async confirmRequest(data) {
    try {
      const response = await fetch('/request/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest' // Optionnel, utile pour reconnaître les requêtes AJAX
        },
        body: JSON.stringify({ data: data })
      });

      if (!response.ok) {
        throw new Error(`Erreur serveur: ${response.status}`);
      }

      const result = await response.json();
      alert(result.message); // Affiche le message de Symfony
    } catch (error) {
      console.error("❌ Erreur lors de l'envoi des données :", error);
      alert("Erreur lors de l'enregistrement de la date.");
    }
  }
}
