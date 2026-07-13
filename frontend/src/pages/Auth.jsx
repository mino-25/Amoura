import { useState } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import apiClient from '../api/client';
import { useAuthStore } from '../store/authStore';
import './Auth.css';

const LOGIN_INITIAL = { email: '', password: '' };
const REGISTER_INITIAL = { nom: '', prenom: '', email: '', password: '' };

export default function Auth() {
  const [tab, setTab] = useState('connexion');
  const [loginForm, setLoginForm] = useState(LOGIN_INITIAL);
  const [registerForm, setRegisterForm] = useState(REGISTER_INITIAL);
  const [errors, setErrors] = useState({});
  const [globalError, setGlobalError] = useState('');
  const [loading, setLoading] = useState(false);

  const login = useAuthStore((state) => state.login);
  const navigate = useNavigate();
  const location = useLocation();
  const redirectTo = location.state?.from?.pathname || '/profil';

  const handleLoginSubmit = async (event) => {
    event.preventDefault();
    setLoading(true);
    setErrors({});
    setGlobalError('');

    try {
      const { data } = await apiClient.post('/login', loginForm);
      const me = await apiClient.get('/me', { headers: { Authorization: `Bearer ${data.token}` } });
      login(data.token, me.data);
      navigate(me.data.role === 'admin' ? '/admin' : redirectTo, { replace: true });
    } catch {
      setGlobalError('Email ou mot de passe incorrect.');
    } finally {
      setLoading(false);
    }
  };

  const handleRegisterSubmit = async (event) => {
    event.preventDefault();
    setLoading(true);
    setErrors({});
    setGlobalError('');

    try {
      const { data } = await apiClient.post('/register', registerForm);
      login(data.token, data.user);
      navigate('/profil', { replace: true });
    } catch (error) {
      if (error.response?.status === 422) {
        setErrors(error.response.data.errors || {});
      } else if (error.response?.status === 409) {
        setGlobalError('Un compte existe déjà avec cet email.');
      } else {
        setGlobalError('Une erreur est survenue, merci de réessayer.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="auth-page">
      <div className="auth-showcase">
        <p className="eyebrow">Espace membre</p>
        <h1>Votre table, vos avantages.</h1>
        <p>Créez un compte pour réserver en ligne, suivre vos visites et accumuler des points de fidélité.</p>
        <ul className="auth-benefits">
          <li>Réservation en ligne 24h/24</li>
          <li>Historique de vos visites</li>
          <li>Programme de fidélité exclusif</li>
          <li>Offres &amp; événements en avant-première</li>
        </ul>
      </div>

      <div className="auth-form-wrap">
        <div className="auth-tabs">
          <button type="button" className={tab === 'connexion' ? 'active' : ''} onClick={() => setTab('connexion')}>
            Connexion
          </button>
          <button type="button" className={tab === 'inscription' ? 'active' : ''} onClick={() => setTab('inscription')}>
            Inscription
          </button>
        </div>

        {globalError && <p className="error auth-global-error">{globalError}</p>}

        {tab === 'connexion' ? (
          <form className="auth-form" onSubmit={handleLoginSubmit}>
            <div className="field">
              <label htmlFor="login-email">Adresse email</label>
              <input
                id="login-email"
                type="email"
                required
                value={loginForm.email}
                onChange={(e) => setLoginForm((f) => ({ ...f, email: e.target.value }))}
              />
            </div>
            <div className="field">
              <label htmlFor="login-password">Mot de passe</label>
              <input
                id="login-password"
                type="password"
                required
                value={loginForm.password}
                onChange={(e) => setLoginForm((f) => ({ ...f, password: e.target.value }))}
              />
            </div>
            <button type="submit" className="btn btn-primary" disabled={loading}>
              {loading ? 'Connexion…' : 'Se connecter'}
            </button>
          </form>
        ) : (
          <form className="auth-form" onSubmit={handleRegisterSubmit}>
            <div className="auth-form-row">
              <div className="field">
                <label htmlFor="reg-prenom">Prénom</label>
                <input
                  id="reg-prenom"
                  required
                  value={registerForm.prenom}
                  onChange={(e) => setRegisterForm((f) => ({ ...f, prenom: e.target.value }))}
                />
                {errors.prenom && <span className="error">{errors.prenom}</span>}
              </div>
              <div className="field">
                <label htmlFor="reg-nom">Nom</label>
                <input
                  id="reg-nom"
                  required
                  value={registerForm.nom}
                  onChange={(e) => setRegisterForm((f) => ({ ...f, nom: e.target.value }))}
                />
                {errors.nom && <span className="error">{errors.nom}</span>}
              </div>
            </div>
            <div className="field">
              <label htmlFor="reg-email">Email</label>
              <input
                id="reg-email"
                type="email"
                required
                value={registerForm.email}
                onChange={(e) => setRegisterForm((f) => ({ ...f, email: e.target.value }))}
              />
              {errors.email && <span className="error">{errors.email}</span>}
            </div>
            <div className="field">
              <label htmlFor="reg-password">Mot de passe</label>
              <input
                id="reg-password"
                type="password"
                required
                value={registerForm.password}
                onChange={(e) => setRegisterForm((f) => ({ ...f, password: e.target.value }))}
              />
              {errors.password && <span className="error">{errors.password}</span>}
              <span className="hint">Au moins 8 caractères, une majuscule, une minuscule et un chiffre.</span>
            </div>
            <button type="submit" className="btn btn-primary" disabled={loading}>
              {loading ? 'Création…' : "S'inscrire"}
            </button>
          </form>
        )}

        <div className="auth-divider">
          <span>ou</span>
        </div>

        <button type="button" className="btn btn-outline" disabled title="Bientôt disponible">
          Continuer avec Google
        </button>
      </div>
    </div>
  );
}
