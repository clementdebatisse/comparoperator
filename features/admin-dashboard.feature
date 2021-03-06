Feature: Admin dashboard
    In order to manage TourOperators
    As an Admin
    I need to be able to add TourOperators
    And tag TourOperators as premium

# La page administrateur est accessible juste en ajoutant /admin dans l'URL
# Bonus : authentification par mot de passe enregistré en session 
# (un mot de passe unique pour tous les administrateurs d'un site).
# Sur cette page, l'administrateur peut ajouter un tour-opérateur.
# L'administrateur peut également ajouter des destinations aux TO parmi une 
# liste fixe.
# Un tour opérateur peut être passé en premium sur cette page.

# @todo Consider doing admin task via RESTish API with some (faked) form of auth
# @todo Consider doing admin task via regular forms