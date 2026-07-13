import { BrowserRouter, Route, Routes } from 'react-router-dom';
import Layout from './components/layout/Layout';
import { AdminRoute, ProtectedRoute } from './routes/ProtectedRoute';
import Home from './pages/Home';
import Menu from './pages/Menu';
import About from './pages/About';
import Contact from './pages/Contact';
import Auth from './pages/Auth';
import Reservation from './pages/Reservation';
import Profile from './pages/Profile';
import AdminLayout from './pages/admin/AdminLayout';
import Dashboard from './pages/admin/Dashboard';
import AdminReservations from './pages/admin/Reservations';
import AdminTables from './pages/admin/Tables';
import AdminClients from './pages/admin/Clients';
import AdminFidelite from './pages/admin/Fidelite';

export default function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route element={<Layout />}>
          <Route path="/" element={<Home />} />
          <Route path="/menu" element={<Menu />} />
          <Route path="/notre-histoire" element={<About />} />
          <Route path="/contact" element={<Contact />} />
          <Route path="/connexion" element={<Auth />} />
          <Route path="/reservation" element={<Reservation />} />

          <Route element={<ProtectedRoute />}>
            <Route path="/profil" element={<Profile />} />
          </Route>
        </Route>

        <Route element={<AdminRoute />}>
          <Route path="/admin" element={<AdminLayout />}>
            <Route index element={<Dashboard />} />
            <Route path="reservations" element={<AdminReservations />} />
            <Route path="tables" element={<AdminTables />} />
            <Route path="clients" element={<AdminClients />} />
            <Route path="fidelite" element={<AdminFidelite />} />
          </Route>
        </Route>
      </Routes>
    </BrowserRouter>
  );
}
