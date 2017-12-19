![Dinder](https://raw.githubusercontent.com/nfqakademija/dinder/master/src/AppBundle/Resources/images/logo.png)

DINDER - Rejoice in Exchange
=======

[![Build Status](https://travis-ci.org/nfqakademija/Dinder.svg?branch=master)](https://travis-ci.org/nfqakademija/Dinder)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nfqakademija/Dinder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nfqakademija/Dinder/?branch=master)
[![Symfony](https://img.shields.io/badge/Symfony-%203.x-green.svg "Supports Symfony 3.x")](https://symfony.com/)

# Table of Contents

* [Project Description](#project-description)
* [Requirements](#requirements)
* [How to Run?](#how-to-run)
* [Team Members](#team-members)
* [License](#license)

# <a name="project-description"></a>Project Description

Often people have unneeded things which they do not use anymore and would like to trade it. But what if you do not know what you want instead?    
Here comes *DINDER* for help! Just add the item you don't need and SWIPE it to exchange with other users! And that is it!

Dinder - developed for exchanging items the fun way!

# <a name="requirements"></a>Requirements

* docker: `>=17.x-ce` 
* docker-compose: `>=1.8.1`

# <a name="how-to-run"></a>How to Run?

```bash
  $ git clone <project>
  $ cd path/to/<project>
  $ ./provision.sh --schema --with-fixtures 
```

* `--schema` - used to recreate project's database;
* `--with-fixtures` - generates demo data (will not work without `--schema`); 

Open `http://localhost:8000`;

# <a name="team-members"></a>Team Members

#### Mentor:

* Sergej Voronov

#### Developers:

* Marius Palevičius
* Skirmantas Bingelis

# <a name="license"></a>License

This project is licensed under the [![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](http://www.gnu.org/licenses/gpl-3.0)
