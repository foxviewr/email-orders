define BackendPackageJson
    $(shell node -p "require('./backend/composer.json').$(1)")
endef
BACKEND_VERSION := $(call BackendPackageJson,version)

define FrontendPackageJson
    $(shell node -p "require('./frontend/package.json').$(1)")
endef
FRONTEND_VERSION := $(call FrontendPackageJson,version)
AUTHOR := $(call FrontendPackageJson,author)
WEBSITE := $(call FrontendPackageJson,website)
