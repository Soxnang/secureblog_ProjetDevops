
## ✅ J3 - Docker + Scan
- Dockerfile multi-étapes créé (builder + runtime minimal)
- .dockerignore ajouté pour exclure node_modules/.git
- Image buildée : `senegalchaussures-api:latest` (45.6MB virtuel)
- Scan Trivy : [À compléter après exécution : 0 vulnérabilité / X Low]
- ⚠️ Note : L'installation de Trivy nécessite sudo ou l'alternative Docker

## ✅ J3 - Docker + Trivy (2026-04-03)
- Dockerfile multi-étapes fonctionnel
- Image buildée : `senegalchaussures-api:latest` (~186MB)
- Scan Trivy : 46 vulnérabilités détectées
  • 2 CRITICAL dans libssl3 (base Alpine) → à corriger via mise à jour image
  • 11 HIGH dans packages npm transitifs → Dependabot gérera
- ✅ Objectif atteint : le scan fonctionne, on a de la visibilité
- ⚠️ Note : La majorité des vulnérabilités viennent de l'image de base, pas de mon code
