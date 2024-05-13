.PHONY: build
build:
	sh scripts/build.sh

.PHONY: commit-new-version
commit-new-version:
	sh scripts/commit-new-version.sh

.PHONY: docker-start
docker-start:
	sh scripts/docker-start.sh

.PHONY: docker-update
docker-update:
	sh scripts/docker-update.sh

.PHONY: setup-svn
setup-svn:
	sh scripts/setup-svn.sh