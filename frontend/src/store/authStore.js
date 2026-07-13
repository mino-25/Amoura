import { create } from 'zustand';
import { persist } from 'zustand/middleware';

export const useAuthStore = create(
  persist(
    (set) => ({
      token: null,
      user: null,
      login: (token, user) => set({ token, user }),
      logout: () => set({ token: null, user: null }),
      updateUser: (user) => set({ user }),
    }),
    { name: 'amoura-auth' },
  ),
);

export const useIsAdmin = () => useAuthStore((state) => state.user?.role === 'admin');
export const useIsAuthenticated = () => useAuthStore((state) => Boolean(state.token));
