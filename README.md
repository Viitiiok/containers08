# containers08

### Numele lucrării de laborator
Dezvoltarea unei aplicații Web bazate pe PHP cu integrare continuă

### Scopul lucrării
- Implementarea unei aplicații PHP care interacționează cu o bază de date SQLite
- Configurarea unui sistem de integrare continuă folosind GitHub Actions
- Scrierea și rularea testelor automate

### Sarcina
1. Crearea unei aplicații PHP cu structură modulară
2. Implementarea claselor pentru lucrul cu baza de date și pagini web
3. Configurarea Docker pentru containerizare
4. Crearea testelor unitare
5. Configurarea GitHub Actions pentru CI/CD

## Descriere
Proiectul implementează o aplicație web modulară în PHP care folosește SQLite ca bază de date, cu integrare continuă prin GitHub Actions.

## Concluzii
Aplicația demonstrează o arhitectură modulară bine structurată
Testele automate asigură calitatea codului
Integrarea continuă reduce riscul de erori
Containerizarea simplifică deploymentul

## Răspunsuri la întrebări

### 1. Integrarea continuă (CI)
Practică de development unde modificările sunt integrate zilnic într-un depozit comun, urmate de build și teste automate pentru detectarea rapidă a erorilor.

### 2. Testele unitare
**Necesitate:**
- Verificare funcționalități individuale
- Prevenire regresii
- Documentare comportament așteptat

**Frecvență executare:**
- La fiecare commit
- Înainte de integrare
- Zilnic în faze active de dezvoltare

### 3. Modificări pentru Pull Request
Adăugare trigger în `.github/workflows/main.yml`:
on:
  pull_request:
    branches: [main]

### 4. Ștergerea imaginilor după testare
Adăugam acest pas la sfârșit:
   - name: Cleanup Docker images
      run: |
         docker rmi containers08 || true
         docker system prune -f
