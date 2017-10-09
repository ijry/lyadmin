<?php

namespace Joli\Jane\OpenApi\Normalizer;

class NormalizerFactory
{
    public static function create()
    {
        $normalizers   = [];
        $normalizers[] = new \Joli\Jane\Runtime\Normalizer\ReferenceNormalizer();
        $normalizers[] = new \Joli\Jane\Runtime\Normalizer\ArrayDenormalizer();
        $normalizers[] = new OpenApiNormalizer();
        $normalizers[] = new InfoNormalizer();
        $normalizers[] = new ContactNormalizer();
        $normalizers[] = new LicenseNormalizer();
        $normalizers[] = new ExternalDocsNormalizer();
        $normalizers[] = new OperationNormalizer();
        $normalizers[] = new PathItemNormalizer();
        $normalizers[] = new ResponseNormalizer();
        $normalizers[] = new HeaderNormalizer();
        $normalizers[] = new BodyParameterNormalizer();
        $normalizers[] = new HeaderParameterSubSchemaNormalizer();
        $normalizers[] = new FormDataParameterSubSchemaNormalizer();
        $normalizers[] = new QueryParameterSubSchemaNormalizer();
        $normalizers[] = new PathParameterSubSchemaNormalizer();
        $normalizers[] = new SchemaNormalizer();
        $normalizers[] = new FileSchemaNormalizer();
        $normalizers[] = new PrimitivesItemsNormalizer();
        $normalizers[] = new XmlNormalizer();
        $normalizers[] = new TagNormalizer();
        $normalizers[] = new BasicAuthenticationSecurityNormalizer();
        $normalizers[] = new ApiKeySecurityNormalizer();
        $normalizers[] = new Oauth2ImplicitSecurityNormalizer();
        $normalizers[] = new Oauth2PasswordSecurityNormalizer();
        $normalizers[] = new Oauth2ApplicationSecurityNormalizer();
        $normalizers[] = new Oauth2AccessCodeSecurityNormalizer();
        $normalizers[] = new JsonReferenceNormalizer();

        return $normalizers;
    }
}
