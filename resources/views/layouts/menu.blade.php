<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 border-bottom-0 sidebar-mini">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link bg-gradient-green">
        <img src="{{asset('AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-bolder text-white" style="font-size: 18px">Cim Seller</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        @if(Auth::user())
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('AdminLTELogo.png')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()?Auth::user()->name:"--" }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                @if ((Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists()))
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ (\Request::route()->getName() == 'dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Tableau de Bord
                        </p>
                    </a>
                </li>
                @endif

                @if ((Auth::user()->roles()->where('libelle', ['CONTROLEUR DE BON DE COMMANDE'])->exists()) || Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['GESTIONNAIRE'])->exists())
                <li class="nav-item">
                    <a href="{{ route('boncommandes.index') }}" class="nav-link {{ (request()->is('boncommandes')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-solid fa-cart-shopping"></i>
                        <p>
                            Bon de Commande
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('detailrecus.details') }}" class="nav-link {{ (request()->is('boncommandes/recus/detailrecus')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list "></i>
                        <p>
                            Détails reçus
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('programmations.index') }}" class="nav-link {{ (request()->is('programmations/*')) ? 'active' : '' }}">
                        <i class="nav-icon far fa-solid fa-list-check"></i>
                        <p>
                            Programmation
                        </p>
                    </a>
                </li>
                @endif


                @if (
                Auth::user()->roles()->where('libelle', 'VENDEUR')->exists()
                || Auth::user()->roles()->where('libelle', 'SUPERVISEUR')->exists()
                || Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists()
                || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists()
                || Auth::user()->roles()->where('libelle', ['VALIDATEUR'])->exists()
                || Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists())
                <li class="nav-item">
                    <a href="{{ route('livraisons.index') }}" class="nav-link {{ (request()->url() == route('livraisons.index')) ? 'active' : '' }}">
                        <i class="nav-icon far fa-solid fa-truck-arrow-right"></i>
                        <p>
                            Livraison
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('livraisons.indexpartielle') }}" class="nav-link {{ (request()->url() == route('livraisons.indexpartielle')) ? 'active' : '' }}">
                        <i class="nav-icon far fa-solid fa-truck-arrow-right"></i>
                        <p>
                            Livraison Partielle
                        </p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->roles()->where('libelle', 'SUIVI')->exists() || Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists())
                <li class="nav-item">
                    <a href="{{ route('livraisons.suivicamion') }}" class="nav-link {{ (request()->url() == route('livraisons.suivicamion')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-solid fa-basket-shopping"></i>
                        <p>
                            Suivi sorties
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('livraisons.suivichauffeur') }}" class="nav-link {{ (request()->url() == route('livraisons.suivichauffeur')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-solid fa-basket-shopping"></i>
                        <p>
                            Suivi Chauffeur
                        </p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->roles()->where('libelle', 'VENDEUR')->exists()
                ||Auth::user()->roles()->where('libelle', 'RECOUVREUR')->exists()
                ||Auth::user()->roles()->where('libelle', 'SUPERVISEUR')->exists()
                ||Auth::user()->roles()->where('libelle', 'CONTROLEUR')->exists()
                ||Auth::user()->roles()->where('libelle', 'CONTROLEUR VENTE')->exists()
                ||Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists()
                ||Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists()
                )
                <li class="nav-header">VENTES</li>
                <li class="nav-item {{request()->route()->getPrefix() == '/ventes' ? 'menu-open':''}}">
                    <a href="#" class="nav-link {{request()->route()->getPrefix() == '/ventes/*' ? 'active':''}}">
                        <i class="nav-icon fas fa-solid fa-hand-holding-dollar"></i>
                        <p>
                            VENTE
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('ventes.index') }}" class="nav-link {{ (request()->is('ventes/index')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-hand-holding-dollar text-cyan"></i>
                                <p>Liste des ventes</p>
                            </a>
                        </li>

                        @if (Auth::user()->roles()->where('libelle', 'SUPERVISEUR')->exists()||Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists()||Auth::user()->roles()->where('libelle', ['RECOUVREUR'])->exists())
                        <li class="nav-item">
                            <a href="{{ route('ventes.indexCreate') }}" class="nav-link {{ (request()->is('ventes/indexCreate')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-hand-holding-dollar text-cyan"></i>
                                <p>Ventes journalières</p>
                            </a>
                        </li>

                        @if (Auth::user()->roles()->where('libelle', 'SUPERVISEUR')->exists()||Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists())
                        <li class="nav-item">
                            <a href="{{ route('ventes.indexControlle') }}" class="nav-link {{ (request()->is('ventes/indexControlle')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-hand-holding-dollar text-cyan"></i>
                                <p>Ventes Contrôlées</p>
                            </a>
                        </li>
                        @endif
                        @endif

                        @if (Auth::user()->roles()->where('libelle', 'VENDEUR')->exists()||Auth::user()->roles()->where('libelle', 'CONTROLEUR VENTE')->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() || Auth::user()->roles()->where('libelle', ['VALIDATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CREANT'])->exists())

                        @if (Auth::user()->roles()->where('libelle', 'VENDEUR')->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() || Auth::user()->roles()->where('libelle', ['VALIDATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CREANT'])->exists())
                        <li class="nav-item">
                            <a href="{{ route('ventes.askUpdate') }}" class="nav-link {{ (request()->is('ventes/askUpdate')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-hand-holding-dollar text-cyan"></i>
                                <p>Vente à modifier</p>
                            </a>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a href="{{ route('edition.solde') }}" class="nav-link {{ (route('edition.solde') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Point du solde.
                                </p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>

                <!--  -->
                @if(Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() || Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists())
                <li class="nav-item {{request()->route()->getPrefix() == '/ventes' ? 'menu-open':''}}">
                    <a href="#" class="nav-link {{request()->route()->getPrefix() == '/ventes/*' ? 'active':''}}">
                        <i class="nav-icon fas fa-solid fa-hand-holding-dollar"></i>
                        <p>
                            Modification & Suppression
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('ventes.validation')}}" class="nav-link">
                                <i class="nav-icon fas fa-solid fa-hand-holding-dollar text-cyan"></i>
                                <p>
                                    Modification en attente
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('ventes.deleteValidation')}}" class="nav-link">
                                <i class="nav-icon fas fa-solid fa-hand-holding-dollar text-cyan"></i>
                                <p>
                                    Suppression en attente
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>
                @endif
                @endif

                @if (Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() || Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists()||Auth::user()->roles()->where('libelle', ['VENDEUR'])->exists() ||Auth::user()->roles()->where('libelle', ['GESTIONNAIRE'])->exists() || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists())
                <li class="nav-header">CONTROLLER</li>
                <li class="nav-item {{request()->route()->getPrefix() == '/controle' ? 'menu-open':''}}">
                    <a href="#" class="nav-link {{request()->route()->getPrefix() == '/controle' ? 'active':''}}">
                        <i class="nav-icon fas fa-solid fa-users-gear"></i>
                        <p>
                            CONTROLES
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        @if (Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() || Auth::user()->roles()->where('libelle', ['VENDEUR'])->exists() || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists())
                        <li class="nav-item">
                            <a href="{{ route('ctlventes.index') }}" class="nav-link {{ (request()->is('controle/*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-cogs  text-cyan"></i>
                                <p>Approvisionement en attente</p>
                            </a>
                        </li>
                        @endif

                        @if (Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() || Auth::user()->roles()->where('libelle', ['GESTIONNAIRE'])->exists())
                        <li class="nav-item">
                            <a href="{{ route('ventes.venteAEnvoyerComptabiliser') }}" class="nav-link {{ (request()->is('ventes/vente-a-envoyer-comptabilise')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-cogs  text-cyan"></i>
                                <p>Vente à Comptabiliser</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if (Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists()
                || Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists()
                || Auth::user()->roles()->where('libelle', ['GESTIONNAIRE'])->exists()
                || Auth::user()->roles()->where('libelle', ['RECOUVREUR'])->exists()
                || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists()
                || Auth::user()->roles()->where('libelle', ['VENDEUR'])->exists())
                <li class="nav-header">FICHIERS</li>
                <li class="nav-item {{request()->route()->getPrefix() == '/fichiers' ? 'menu-open':''}}">
                    <a href="#" class="nav-link {{(request()->route()->getPrefix() == '/fichiers') ? 'active':''}}">
                        <i class="nav-icon fas fa-regular fa-folder-open"></i>
                        <p>
                            Fichiers
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CREANT'])->exists())
                        <li class="nav-item">
                            <a href="{{ route('avaliseurs.index') }}" class="nav-link {{ (request()->is('fichiers/avaliseurs/*')) ? 'active' : '' }}">
                                <i class="nav-icon far fa-regular fa-handshake text-cyan"></i>
                                <p>
                                    Avaliseur
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('camions.index') }}" class="nav-link {{ (request()->is('fichiers/camions/*')) ? 'active' : '' }}">
                                <i class="nav-icon far fa-solid fa-truck-moving  text-cyan"></i>
                                <p>
                                    Camion
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('chauffeurs.index') }}" class="nav-link {{ (request()->is('fichiers/chauffeurs/*')) ? 'active' : '' }}">
                                <i class="nav-icon far fa-solid fa-user-tag  text-cyan"></i>
                                <p>
                                    Chauffeur
                                </p>
                            </a>
                        </li>
                        @endif

                        @if (Auth::user()->roles()->where('libelle', 'GESTION CLIENT')->exists() || Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists() || Auth::user()->roles()->where('libelle', 'VENDEUR')->exists())
                        <!-- les actifs -->
                        <li class="nav-item">
                            <a href="{{ route('newclient.index') }}" class="nav-link {{ (request()->is('newclient/index')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-hand-holding-hand  text-cyan"></i>
                                <p>
                                    Client actifs
                                </p>
                            </a>
                        </li>
                        <!-- les inactifs -->
                        <li class="nav-item">
                            <a href="{{ route('newclient.inactif') }}" class="nav-link {{ (request()->is('newclient/index/inactif')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-hand-holding-hand  text-cyan"></i>
                                <p>
                                    Client inactifs
                                </p>
                            </a>
                        </li>

                        <!-- les befs -->
                        <li class="nav-item">
                            <a href="{{ route('newclient.befs') }}" class="nav-link {{ (request()->is('newclient/index/befs')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-hand-holding-hand  text-cyan"></i>
                                <p>
                                    Client befs
                                </p>
                            </a>
                        </li>

                        @if (Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists())
                        <li class="nav-item">
                            <a href="{{ route('newclient.oldClients') }}" class="nav-link {{ (request()->is('newclient/indexOld')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-hand-holding-hand  text-cyan"></i>
                                <p>
                                    Anciens Clients migrés
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('newclient.oldClientsNotInTheNewSystem') }}" class="nav-link {{ (request()->is('newclient/indexOldNotExistInTheNewSystem')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-hand-holding-hand  text-cyan"></i>
                                <p>
                                    Anciens Clients non migrés
                                </p>
                            </a>
                        </li>

                        @endif


                        @if (Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists())

                        <li class="nav-item">
                            <a href="{{ route('agent.index') }}" class="nav-link {{ (request()->is('agent/*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-user  text-cyan"></i>
                                <p>
                                    Agent
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('banques.index')}}" class="nav-link {{ (request()->is('fichiers/banques/*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-building-columns  text-cyan"> </i>
                                <p>Banque</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fournisseurs.index') }}" class="nav-link {{ (request()->is('fichiers/fournisseurs/*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-truck-field  text-cyan"> </i>
                                <p>Fournisseurs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('produits.index') }}" class="nav-link {{ (request()->is('fichiers/produits/*')) ? 'active' : '' }}">
                                <i class="nav-icon fa-brands fa-product-hunt  text-cyan"></i>
                                <p>Produits</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('representants.index') }}" class="nav-link {{ (request()->is('fichiers/representants/*')) ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-person-chalkboard  text-cyan"></i>
                                <p>Représentants</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('zones.index') }}" class="nav-link {{ (request()->is('fichiers/zones/*')) ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-earth-africa  text-cyan"></i>
                                <p>Zones</p>
                            </a>
                        </li>
                        @endif
                        @endif
                    </ul>
                </li>

                @if (Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists()
                || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists()
                || Auth::user()->roles()->where('libelle', ['GESTIONNAIRE'])->exists()
                || Auth::user()->roles()->where('libelle', ['RECOUVREUR'])->exists()
                || Auth::user()->roles()->where('libelle', ['CREANT'])->exists())

                <li class="nav-header">EDITION</li>

                <li class="nav-item {{request()->route()->getPrefix() == '/edition' ? 'menu-open':''}}">
                    <a href="#" class="nav-link {{request()->route()->getPrefix() == '/edition' ? 'active':''}}">
                        <i class="nav-icon fas fa-regular fa-file"></i>
                        <p>
                            Visualisations & états
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        @if(Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists()||Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists()|| Auth::user()->roles()->where('libelle', ['GESTIONNAIRE'])->exists() || Auth::user()->roles()->where('libelle', ['CREANT'])->exists())
                        @if(Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists() || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists() || Auth::user()->roles()->where('libelle', ['CREANT'])->exists())
                        <li class="nav-item">
                            <a href="{{ route('edition.stock') }}" class="nav-link {{ (route('edition.stock') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Point des stocks livrés
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('edition.stockValider') }}" class="nav-link {{ (route('edition.stockValider') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Point des stocks non livrés
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('edition.etatLivraisonPeriode') }}" class="nav-link {{ (route('edition.etatLivraisonPeriode') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Etat des Livraison
                                </p>
                            </a>
                        </li>
                        @endif
                        @endif

                        @if (Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists() || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists() || Auth::user()->roles()->where('libelle', ['CREANT'])->exists())
                        <li class="nav-item">
                            <a href="{{ route('edition.etatReglementperiode') }}" class="nav-link {{ (route('edition.etatReglementperiode') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Etat des reglement
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('edition.solde') }}" class="nav-link {{ (route('edition.solde') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Point du solde.
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('edition.etatCompte') }}" class="nav-link {{ (route('edition.etatCompte') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Etat des Comptes.
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('edition.revouvrement') }}" class="nav-link {{ (route('edition.revouvrement') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Crédit à recouvrir
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('accusedocuments.indexAll') }}" class="nav-link {{ (route('accusedocuments.indexAll') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Etat des accusés
                                </p>
                            </a>
                            <a href="{{ route('edition.etatCaProgPeriode') }}" class="nav-link {{ (route('edition.etatCaProgPeriode') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Etat des Camions
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('edition.etatLivraisonPeriode') }}" class="nav-link {{ (route('edition.etatLivraisonPeriode') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Etat des Livraison
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('edition.EtatGenePeriode') }}" class="nav-link {{ (route('edition.EtatGenePeriode') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Etat versement Journalier
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('edition.compteApprovisionnement') }}" class="nav-link {{ (route('edition.compteApprovisionnement') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Approvisionnements
                                </p>
                            </a>
                        </li>

                        @endif

                        <li class="nav-item">
                            <a href="{{ route('edition.etatlivraisoncde') }}" class="nav-link {{ (route('edition.etatlivraisoncde') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Etat livraison commande
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('edition.etatventeperiode') }}" class="nav-link {{ (route('edition.etatventeperiode') == url()->current()) ? 'active' : '' }}">
                                <i class="nav-icon far  fa-list text-cyan"></i>
                                <p>
                                    Etat des ventes
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                @if (Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists())
                <li class="nav-header">CONFIGURATION</li>
                <li class="nav-item {{request()->route()->getPrefix() == '/configurations' ? 'menu-open':''}}">
                    <a href="#" class="nav-link {{request()->route()->getPrefix() == '/configurations' ? 'active':''}}">
                        <i class="nav-icon fas fa-solid fa-gears"></i>
                        <p>
                            Configuration
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('categoriefournisseurs.index') }}" class="nav-link {{ (request()->is('configurations/categoriefournisseurs/*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-truck-field  text-cyan"> </i>
                                <p>Catégorie Fournisseur</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('typeclients.index') }}" class="nav-link {{ (request()->is('configurations/typeclients/*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-hand-holding-hand  text-cyan"></i>
                                <p>Type Client</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('typecommandes.index') }}" class="nav-link {{ (request()->is('configurations/typecommandes/*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-cart-flatbed  text-cyan"></i>
                                <p>Type Commande</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('typedetailrecus.index') }}" class="nav-link {{ (request()->is('configurations/typedetailrecus/*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-file-invoice  text-cyan"></i>
                                <p>Type Détail Reçu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('typedocuments.index') }}" class="nav-link {{ (request()->is('configurations/typedocuments/*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-file-shield  text-cyan"></i>
                                <p>Type Document</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('typeavaliseurs.index') }}" class="nav-link {{ (request()->is('configurations/typeavaliseurs/*')) ? 'active' : '' }}">
                                <i class="nav-icon far fa-regular fa-handshake  text-cyan"></i>
                                <p>Type Avaliseur</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('typeproduits.index')}}" class="nav-link {{ (request()->is('configurations/typeproduits/*')) ? 'active' : '' }}">
                                <i class="nav-icon fa-brands fa-product-hunt  text-cyan"></i>
                                <p>Type Produit</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('compagnieassurances.index')}}" class="nav-link  {{ (request()->is('configurations/compagnieassurances/*')) ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-car-burst  text-cyan"></i>
                                <p>Compagnie Assurance</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('departements.index')}}" class="nav-link {{ (request()->is('configurations/departements/*')) ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-warehouse  text-cyan"></i>
                                <p>Département</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('marques.index')}}" class="nav-link {{ (request()->is('configurations/marques/*')) ? 'active' : '' }}">
                                <i class="nav-icon fa-solid fa-taxi text-cyan"></i>
                                <p>Marque</p>
                            </a>
                        </li>
                    </ul>
                    @endif
                    @endif
                </li>
                @endif

                @if (Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists() || Auth::user()->roles()->where('libelle', 'COMPTABLE')->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists())
                <li class="nav-header">COMPTABILITE</li>
                <li class="nav-item {{request()->route()->getPrefix() == '/comptabilite' ? 'menu-open':''}}">
                    <a href="#" class="nav-link {{request()->route()->getPrefix() == '/comptabilite' ? 'active':''}}">
                        <i class="nav-icon fas fa-solid fa-calculator"></i>
                        <p>
                            Comptabilité
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('ventes.venteAComptabiliser') }}" class="nav-link {{ (request()->is('/comptabilite/vente-a-comptabilise')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-money-bill-alt  text-cyan"></i>
                                <p>Toutes Ventes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ventes.getVenteAtraiter') }}" class="nav-link {{ (request()->is('/comptabilite/vente-a-traiter')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-money-bill-alt  text-cyan"></i>
                                <p>Ventes à traiter</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ventes.getVenteTotaux') }}" class="nav-link {{ (request()->is('/comptabilite/vente-totaux')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid bi-database-add"></i>
                                <p>Totaux des ventes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ventes.viewVenteTraiter') }}" class="nav-link {{ (request()->is('/comptabilite/viewVenteTraiter')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-file-alt  text-cyan"></i>
                                <p>Exporter Vente à Comptabiliser</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('ventes.viewVenteComptabiliser') }}" class="nav-link {{ (request()->is('/comptabilite/viewVenteComptabiliser')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-file-alt  text-cyan"></i>
                                <p>Exporter Vente Comptabiliser</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('ventes.venteAComptabiliserDeleted') }}" class="nav-link {{ (request()->is('/comptabilite/viewVenteComptabiliser')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-file-alt  text-cyan"></i>
                                <p>Vente supprimées</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('ventes.venteAComptabiliserUpdated') }}" class="nav-link {{ (request()->is('/comptabilite/viewVenteComptabiliser')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-file-alt  text-cyan"></i>
                                <p>Vente Modifiées</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists())
                <li class="nav-header">ADMINISTRATION</li>
                <li class="nav-item {{request()->route()->getPrefix() == '/admin' ? 'menu-open':''}}">
                    <a href="#" class="nav-link {{request()->route()->getPrefix() == '/admin' ? 'active':''}}">
                        <i class="nav-icon fas fa-solid fa-users-gear"></i>
                        <p>
                            Administration
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ (request()->is('admin/users/*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-user-gear  text-cyan"></i>
                                <p>Utilisateurs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link {{ (request()->is('admin/roles/*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-solid fa-key  text-cyan"></i>
                                <p>Rôles</p>
                            </a>
                        </li>
                    </ul>

                    <!-- LES ACTIONS DES UTILISATEURS -->
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.actions') }}" class="nav-link {{ (request()->is('admin/users/actions')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-solid fa-user-gear  text-cyan"></i>
                                <p>Actions des utilisateurs</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

            </ul>
        </nav>
        @endif
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->

</aside>