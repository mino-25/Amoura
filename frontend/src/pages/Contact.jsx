import { useState } from 'react';
import apiClient from '../api/client';
import './Contact.css';

const INITIAL_FORM = { prenom: '', nom: '', email: '', message: '' };

export default function Contact() {
  const [form, setForm] = useState(INITIAL_FORM);
  const [status, setStatus] = useState('idle');
  const [errors, setErrors] = useState({});

  const handleChange = (event) => {
    const { name, value } = event.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    setStatus('sending');
    setErrors({});

    try {
      await apiClient.post('/contact', form);
      setStatus('sent');
      setForm(INITIAL_FORM);
    } catch (error) {
      setErrors(error.response?.data?.errors || {});
      setStatus('error');
    }
  };

  return (
    <div className="contact-page">
      <div className="contact-grid">
        <div className="contact-info">
          <p className="eyebrow">Nous trouver</p>
          <h1>Venez nous rendre visite</h1>

          <div className="contact-item">
            <h3>Adresse</h3>
            <p>12 rue de la Gastronomie</p>
            <p>75006 Paris, France</p>
          </div>

          <div className="contact-item">
            <h3>Téléphone</h3>
            <p>+33 1 23 45 67 89</p>
          </div>

          <div className="contact-item">
            <h3>Email</h3>
            <p>contact@amoura-restaurant.fr</p>
          </div>

          <div className="contact-item">
            <h3>Horaires</h3>
            <p>Mar–Sam : 12h–14h30 · 19h–22h30</p>
            <p>Dimanche : 12h–15h · Lundi fermé</p>
          </div>
        </div>

        <div className="contact-form-wrap">
          <h2>Envoyez-nous un message</h2>

          {status === 'sent' ? (
            <p className="badge badge-success">Votre message a bien été envoyé, merci !</p>
          ) : (
            <form className="contact-form" onSubmit={handleSubmit}>
              <div className="contact-form-row">
                <div className="field">
                  <label htmlFor="prenom">Prénom</label>
                  <input id="prenom" name="prenom" value={form.prenom} onChange={handleChange} required />
                  {errors.prenom && <span className="error">{errors.prenom}</span>}
                </div>
                <div className="field">
                  <label htmlFor="nom">Nom</label>
                  <input id="nom" name="nom" value={form.nom} onChange={handleChange} required />
                  {errors.nom && <span className="error">{errors.nom}</span>}
                </div>
              </div>

              <div className="field">
                <label htmlFor="email">Email</label>
                <input id="email" name="email" type="email" value={form.email} onChange={handleChange} required />
                {errors.email && <span className="error">{errors.email}</span>}
              </div>

              <div className="field">
                <label htmlFor="message">Message</label>
                <textarea id="message" name="message" rows={5} value={form.message} onChange={handleChange} required />
                {errors.message && <span className="error">{errors.message}</span>}
              </div>

              {status === 'error' && !Object.keys(errors).length && (
                <p className="error">Une erreur est survenue, merci de réessayer.</p>
              )}

              <button type="submit" className="btn btn-primary" disabled={status === 'sending'}>
                {status === 'sending' ? 'Envoi…' : 'Envoyer le message'}
              </button>
            </form>
          )}

          <div className="contact-map" aria-hidden="true">
            Intégration Google Maps API
          </div>
        </div>
      </div>
    </div>
  );
}
