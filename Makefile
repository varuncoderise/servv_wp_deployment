Default: help

CODERISE_ENV      := dev
DOCKER_ENV        := 
CODERISE_NS       := coderise
ECR_REGION        := us-east-2
VERSION           := $(API_VERSION)
OPTS              := $(HELM_OPTS)
IMAGE_TAG         := $(BITBUCKET_BUILD_NUMBER)
APP               := $(APP)

.PHONY:deploy
deploy: build install

.PHONY:build
build:
	  $(aws ecr get-login --no-include-email --region $(ECR_REGION) | sed 's|https://||') \
	  docker build --build-arg SSH_PRIVATE_KEY=$(BITBUCKET_SSH_KEY) -t 058291926324.dkr.ecr.$(ECR_REGION).amazonaws.com/$(APP)$(DOCKER_ENV):$(IMAGE_TAG) . ;  \
	  docker push 058291926324.dkr.ecr.$(ECR_REGION).amazonaws.com/$(APP)$(DOCKER_ENV):$(IMAGE_TAG) ; \
	  echo Completed $(APP) build... ; \
	  cd .. ;

.PHONY:install
install:
	  cd ./helm/$(APP)-chart ; \
	  helm upgrade --install $(APP) -f $(CODERISE_ENV)/$(VERSION)/values.yaml --set image.tag=$(IMAGE_TAG) -n $(CODERISE_NS) . ; \
	  cd ../../.. ; 

.PHONY: uninstall
uninstall:  
	helm delete $(APP) -n $(CODERISE_NS) ;

.PHONY: help
help:
	echo "Install CODERISE blog"
	echo "Dev: make build APP=appName ENV=dev TAG=1.0 REGION=us-east-2 API_VERSION=v6 NAMESPACE=staging"
	echo "Test: make build APP=appName ENV=dev TAG=1.0 REGION=us-east-2 API_VERSION=v6 NAMESPACE=staging"
	echo "Production: make build APP=appName ENV=prod TAG=1.0 REGION=us-west-2 API_VERSION=v6 NAMESPACE=services"
	echo "Delete: make uninstall ENV=dev API_VERSION=v6"