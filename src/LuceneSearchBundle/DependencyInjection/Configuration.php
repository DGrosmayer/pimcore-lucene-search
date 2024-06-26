<?php

namespace LuceneSearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('lucene_search');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->booleanNode('enabled')
                    ->isRequired()
                    ->info('Enable and configure the search frontend if you want to include a full text search on your website.')
                ->end()
                ->booleanNode('fuzzy_search_results')
                    ->isRequired()
                    ->info('Fuzzy search results: When enabled, a fuzzy search is performed. The search will automatically include related terms.')
                ->end()
                ->booleanNode('search_suggestion')
                    ->isRequired()
                    ->info('Search suggestions: When enabled, a fuzzy search for similar search terms is performed. If no results could be found for the search term entered by the user, similar search terms are presented as suggestions.')
                ->end()
                ->booleanNode('own_host_only')
                    ->isRequired()
                    ->info('Own Host only: Limit search (and crawling) results to results from the current seed (sub-)domain only.')
                ->end()
                ->booleanNode('allow_subdomains')
                    ->isRequired()
                    ->info('Allow Subdomains: Limit search (and crawling) results to allow / disallow subomdains of current seed.')
                ->end()
                ->arrayNode('seeds')
                    ->isRequired()
                    ->info('Start-Urls (Seeds): Specify start URLs for the crawler. Please enter with protocol! e.g. http://www.pimcore.org and enter a starting URL on your main domain first and any subdomains next, because the domain of the first URL will be used as the main domain for sitemap generation.')
                ->prototype('scalar')->end()
                ->end()
                ->scalarNode('categories')
                    ->info('Categories: If search results should be displayed by categories, please enter all valid categories here. The crawler sorts a page into a category if it contains a html meta tag with the name cat.')
                ->end()
                ->arrayNode('filter')
                    ->children()
                        ->booleanNode('allow_query_in_url')
                            ->defaultFalse()
                            ->info('When true, LuceneSearch will crawl urls with query fragments.')
                        ->end()
                        ->booleanNode('allow_hash_in_url')
                            ->defaultFalse()
                            ->info('When true, LuceneSearch will crawl urls with hash fragments.')
                        ->end()
                        ->arrayNode('valid_links')
                            ->info('Regex for valid Uris: Specify PREG regex with start and end delimiter to define allowed links. e.g. @^http://www\.pimcore\.org*@i')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('user_invalid_links')
                            ->info('Regex for forbidden Uris: Specify PREG regex for links which should be ignored by the crawler. The crawler does not even follow these links e.g. @^www\.pimcore\.org\/community*@i')
                            ->prototype('scalar')->end()
                        ->end()
                            ->scalarNode('core_invalid_links')
                            ->info('Invalid Links/Extensions defined by core. You can\'nt override this.')
                            ->cannotBeOverwritten()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('allowed_mime_types')
                    ->info('Allowed MIME-Types. (Supported: text/html, application/pdf')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('allowed_schemes')
                    ->prototype('scalar')->end()
                    ->info('Allowed Schemes: Define which url Schemes are allowed. (eg. http and/or https). Default is http.')
                ->end()
                ->arrayNode('crawler')
                    ->children()
                        ->integerNode('max_link_depth')
                            ->info('Maximum link depth: To avoid loops produced by relative links on a website, a maximum link depth needs to be set. Please choose the value suited to the website to crawl, the default value is 15.')
                        ->end()
                        ->integerNode('max_download_limit')
                            ->info('Maximum links to crawl: Constrain crawler to a specific limit of crawled links. Defaults is 0 which means no limit.')
                        ->end()
                        ->floatNode('content_max_size')
                            ->info('Maximum content size (in MB): crawler ignores resources if its size exceeds limit (mostly useful for asset indexing). Defaults is 0 which means no limit.')
                        ->end()
                        ->scalarNode('content_start_indicator')
                            ->info('You can limit the page content relevant for searching by surrounding it with certain html comments. The crawler will still parse the entire page to find links, but only the specified area wihin your html comments is used when searching for a term. String specifying content start for search.')
                        ->end()
                        ->scalarNode('content_end_indicator')
                            ->info('String specifying content end for search.')
                        ->end()
                        ->scalarNode('content_exclude_start_indicator')
                            ->info('String specifying exclude content start for search.')
                        ->end()
                        ->scalarNode('content_exclude_end_indicator')
                            ->info('String specifying exclude content end for search.')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('locale')
                    ->children()
                        ->booleanNode('ignore_language')
                            ->info('Receive search results from all languages, set to false to limit search results to the current language only. The current language is retrieved from the registy, the language of any page in the search result index is extracted by the crawler (Content-Language Http header, html tag lang attribute or html meta tag content-language)')
                            ->isRequired()
                        ->end()
                        ->booleanNode('ignore_country')
                            ->isRequired()
                            ->info('Receive search results from all countries, set to false to limit search results to country only. The current country is retrieved from the search result index. it is extracted by the crawler (html meta tag country)')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('restriction')
                    ->children()
                        ->booleanNode('enabled')
                            ->isRequired()
                            ->info('Document Restriction: Ignore Document restrictions. Set to true if you\'re using the Pimcore/MembersBundle')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('boost')
                    ->children()
                        ->integerNode('documents')
                            ->info('Document Boost Factor')
                        ->end()
                        ->integerNode('assets')
                            ->info('Asset Boost Factor')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('view')
                    ->children()
                        ->integerNode('max_per_page')
                            ->info('Max Results per Page')
                        ->end()
                        ->integerNode('max_suggestions')
                            ->info('Max Suggestions')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
