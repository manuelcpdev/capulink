/**
 * Colección de funcións reutilizábeis
 */
export class HelperService {
  /**
   * Xera unha URL de VirusTotal a partires da URL pasada como parámetro
   * @param {string} url URL obxectivo da conversión
   * @returns {string} URL de VirusTotal
   */
  public static convertirAVirusTotalURL(url: string): string {
    const encoded = btoa(url)
      .replace(/\+/g, '-')
      .replace(/\//g, '_')
      .replace(/=+$/, '');
    return `https://www.virustotal.com/gui/url/${encoded}/detection`;
  }
}
